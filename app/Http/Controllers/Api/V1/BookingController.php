<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\{Booking, ProviderProfile};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\NotificationService;
use App\Traits\ApiResponse;

class BookingController extends Controller
{
    use ApiResponse;
    public function index()
    {
        $bookings = Booking::with(['provider.user','review'])
            ->where('customer_id', auth()->id())
            ->latest()
            ->paginate(10);

        return $this->paginated($bookings, 'Bookings fetched.');
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'provider_id'  => 'required|exists:provider_profiles,id',
            'service_type' => 'required|string|max:100',
            'scheduled_at' => 'required|date|after:now',
            'address'      => 'required|string',
            'notes'        => 'nullable|string|max:500',
        ]);

        if ($v->fails()) return $this->validationError($v->errors());

        $provider = ProviderProfile::find($request->provider_id);

        if (! $provider?->is_available) {
            return $this->error('Provider is not available.', 422);
        }

        $booking = Booking::create([
            'customer_id'  => auth()->id(),
            'provider_id'  => $request->provider_id,
            'service_type' => $request->service_type,
            'scheduled_at' => $request->scheduled_at,
            'address'      => $request->address,
            'notes'        => $request->notes,
            'amount'       => $provider->hourly_rate,
            'status'       => 'pending',
        ]);
        NotificationService::bookingCreated($booking);

        return $this->created($booking->load('provider.user'), 'Booking created successfully.');
    }

    public function show($id)
    {   
        $getRole = auth('web')->user();
        if($getRole->role->slug === 'provider'){
            $booking = Booking::with(['provider.user','customer','review'])
                ->where('provider_id', auth('web')->user()->id)
                ->find($id);
        }else{
            $booking = Booking::with(['provider.user','customer','review'])
                ->where('customer_id', auth()->id())
                ->find($id);
        }

        if (! $booking) return $this->notFound('Booking not found.');

        return $this->success($booking);
    }

    public function cancel($id)
    {
        $booking = Booking::where('customer_id', auth()->id())->find($id);

        if (! $booking) return $this->notFound('Booking not found.');

        if (! in_array($booking->status, ['pending','confirmed'])) {
            return $this->error('Booking cannot be cancelled at this stage.', 422);
        }

        $booking->update(['status' => 'cancelled']);

        return $this->success(null, 'Booking cancelled.');
    }

    public function providerJobs(Request $request)
    {
        $jobs = Booking::with(['customer:id,name,phone,avatar','review'])
            ->where('provider_id', auth('web')->user()->id)
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(10);

        return $this->paginated($jobs, 'Jobs fetched.');
    }

    public function accept($id)
    {
        $booking = Booking::where('provider_id', auth('web')->user()->id)
                        ->where('status', 'pending')
                        ->find($id);

        if (! $booking) return $this->notFound('Job not found or already processed.');

        $booking->update(['status' => 'confirmed']);

        NotificationService::bookingAccepted($booking);

        return $this->success($booking, 'Job accepted.');
    }

    public function updateStatus(Request $request, $id)
    {
        $v = Validator::make($request->all(), [
            'status' => 'required|in:on_the_way,in_progress,completed',
        ]);

        if ($v->fails()) return $this->validationError($v->errors());

        $booking = Booking::where('provider_id', auth('web')->user()->id)->find($id);

        if (! $booking) return $this->notFound('Job not found.');

        $booking->update(['status' => $request->status]);

        if ($request->status === 'completed') {
            auth('web')->user()->providerProfile->increment('total_jobs');
        }
        NotificationService::jobStatusUpdated($booking);

        return $this->success($booking, 'Status updated.');
    }

    public function decline($id)
    {
        $booking = Booking::where('provider_id', auth('web')->user()->id)
                        ->where('status', 'pending')
                        ->find($id);

        if (! $booking) return $this->notFound('Job not found.');

        $booking->update(['status' => 'cancelled']);

        NotificationService::send(
            $booking->customer_id,
            'Booking Declined',
            'Your booking request was declined. Try another provider.',
            'booking', $booking->id
        );

        return $this->success(null, 'Job declined.');
    }
}