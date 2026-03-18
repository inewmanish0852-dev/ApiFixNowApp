<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\{
    ProviderController,
    BookingController,
    ReviewController,
    ProfileController,
    ChatController,
};
use App\Http\Controllers\Api\V1\Admin\{
    AdminUserController,
    AdminBookingController,
    AdminDashboardController,
};

Route::get('test', function () {
    return response()->json([
        'message' => 'Test route',
    ]);
});

Route::prefix('v1')->group(function () {

    // ── Public ──────────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login',    [AuthController::class, 'login']);
    });

    Route::post('profile/password', [ProfileController::class, 'changePassword']);
    Route::post('profile/avatar',   [ProfileController::class, 'uploadAvatar']);

    Route::get('providers',        [ProviderController::class, 'index']);
    Route::get('providers/{id}',   [ProviderController::class, 'show']);
    Route::get('providers/category/{category}', [ProviderController::class, 'byCategory']);

    Route::prefix('notifications')->group(function () {
    Route::get('/',          [NotificationController::class, 'index']);
    Route::get('unread',     [NotificationController::class, 'unreadCount']);
    Route::patch('{id}/read',[NotificationController::class, 'markRead']);
    Route::patch('read-all', [NotificationController::class, 'markAllRead']);
});

    // ── Authenticated ────────────────────────────────────
    Route::middleware('auth:api')->group(function () {

        // Auth helpers
        Route::prefix('auth')->group(function () {
            Route::get('me',       [AuthController::class, 'me']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::post('logout',  [AuthController::class, 'logout']);
        });

        // Profile (all roles)
        Route::prefix('profile')->group(function () {
            Route::put('/',       [ProfileController::class, 'update']);
            Route::post('avatar', [ProfileController::class, 'uploadAvatar']);
        });

        // Reviews (read — any auth user)
        Route::get('providers/{id}/reviews', [ReviewController::class, 'index']);

        // ── Customer ──────────────────────────────────────
        Route::middleware('role:customer')->group(function () {
            Route::apiResource('bookings', BookingController::class)->only(['index', 'store', 'show']);
            Route::patch('bookings/{id}/cancel', [BookingController::class, 'cancel']);
            Route::post('bookings/{id}/review', [ReviewController::class, 'store']);
        });

        // ── Provider ──────────────────────────────────────
        Route::middleware('role:provider')->group(function () {

            Route::patch('jobs/{id}/decline', [BookingController::class, 'decline']);

            Route::put('provider/profile',         [ProfileController::class, 'updateProviderProfile']);
            Route::post('provider/profile/skills', [ProfileController::class, 'syncSkills']);
            Route::patch('provider/availability',  [ProfileController::class, 'toggleAvailability']);

            // Job management
            Route::get('jobs',                     [BookingController::class, 'providerJobs']);
            Route::patch('jobs/{id}/accept',       [BookingController::class, 'accept']);
            Route::patch('jobs/{id}/status',       [BookingController::class, 'updateStatus']);

            // Earnings
            Route::get('provider/earnings',        [ProfileController::class, 'earnings']);
        });

        // ── Admin ─────────────────────────────────────────
        Route::middleware('role:admin')->prefix('admin')->group(function () {

            // Dashboard
            Route::get('dashboard', [AdminDashboardController::class, 'index']);

            // Users
            Route::get('users',                  [AdminUserController::class, 'index']);
            Route::get('users/{id}',             [AdminUserController::class, 'show']);
            Route::patch('users/{id}/toggle',    [AdminUserController::class, 'toggleActive']);
            Route::patch('users/{id}/verify',    [AdminUserController::class, 'verify']);

            // Bookings
            Route::get('bookings',               [AdminBookingController::class, 'index']);
            Route::get('bookings/{id}',          [AdminBookingController::class, 'show']);
            Route::patch('bookings/{id}/status', [AdminBookingController::class, 'updateStatus']);
        });

        // ── Chat (customer + provider) ─────────────────────
        Route::middleware('role:customer,provider')->group(function () {
            Route::get('chat/{bookingId}',    [ChatController::class, 'messages']);
            Route::post('chat/{bookingId}',   [ChatController::class, 'send']);
        });
    });
});