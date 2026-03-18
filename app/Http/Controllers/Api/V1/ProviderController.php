<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\{ProviderProfile, User};
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class ProviderController extends Controller
{
    use ApiResponse;
    public function index(Request $request)
    {
        $providers = ProviderProfile::with(['user','skills'])
            ->where('is_available', true)
            ->whereHas('user', fn($q) => $q->where('is_active', true)->where('is_verified', true))
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->when($request->city, fn($q) => $q->whereHas('user', fn($u) => $u->where('city','like','%'.$request->city.'%')))
            ->orderByDesc('rating')
            ->paginate(15);

        return $this->paginated($providers, 'Providers fetched.');
    }

    public function show($id)
    {
        $provider = ProviderProfile::with(['user','skills'])->find($id);

        if (! $provider) return $this->notFound('Provider not found.');

        return $this->success($provider, 'Provider details fetched.');
    }

    public function byCategory($category)
    {
        $providers = ProviderProfile::with(['user','skills'])
            ->where('category', $category)
            ->where('is_available', true)
            ->orderByDesc('rating')
            ->paginate(15);

        return $this->paginated($providers);
    }
}