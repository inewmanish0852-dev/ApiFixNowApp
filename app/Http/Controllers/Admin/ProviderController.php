<?php
// =====================================================
// FILE: app/Http/Controllers/Admin/ProviderController.php
// =====================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index(Request $request)
    {
        $query = Provider::with('user')->latest();

        if ($request->filled('status'))
            $query->where('verification_status', $request->status);

        if ($request->filled('search'))
            $query->whereHas('user', fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
            );

        $providers = $query->paginate(20)->withQueryString();

        $counts = [
            'all'       => Provider::count(),
            'pending'   => Provider::pending()->count(),
            'verified'  => Provider::verified()->count(),
            'rejected'  => Provider::where('verification_status', 'rejected')->count(),
            'suspended' => Provider::suspended()->count(),
        ];

        return view('admin.providers.index', compact('providers', 'counts'));
    }

    public function pending()
    {
        $providers = Provider::with('user')->pending()->latest()->paginate(20);
        return view('admin.providers.pending', compact('providers'));
    }

    public function show(Provider $provider)
    {
        $provider->load(['user', 'services.category', 'bookings.customer', 'reviews.customer']);

        $stats = [
            'total_bookings' => $provider->bookings()->count(),
            'completed'      => $provider->bookings()->where('status', 'completed')->count(),
            'cancelled'      => $provider->bookings()->where('status', 'cancelled')->count(),
            'total_earnings' => $provider->bookings()->where('payment_status', 'paid')->sum('total_amount'),
        ];

        return view('admin.providers.show', compact('provider', 'stats'));
    }

    public function verify(Provider $provider)
    {
        $provider->update([
            'verification_status' => 'verified',
            'verified_at'         => now(),
            'verified_by'         => auth()->id(),
            'rejection_reason'    => null,
        ]);
        $provider->user->update(['is_active' => true]);

        return back()->with('success', "Provider {$provider->user->name} verified successfully!");
    }

    public function unverify(Provider $provider)
    {
        $provider->update([
            'verification_status' => 'pending',
            'verified_at'         => null,
            'verified_by'         => null,
        ]);
        return back()->with('success', "Provider moved back to pending.");
    }

    public function reject(Request $request, Provider $provider)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $provider->update([
            'verification_status' => 'rejected',
            'rejection_reason'    => $request->reason,
        ]);

        return back()->with('success', "Provider rejected.");
    }

    public function suspend(Request $request, Provider $provider)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $provider->update([
            'verification_status' => 'suspended',
            'rejection_reason'    => $request->reason,
        ]);
        $provider->user->update(['is_active' => false]);

        return back()->with('success', "Provider suspended.");
    }

    public function unsuspend(Provider $provider)
    {
        $provider->update(['verification_status' => 'verified']);
        $provider->user->update(['is_active' => true]);

        return back()->with('success', "Provider unsuspended.");
    }
}