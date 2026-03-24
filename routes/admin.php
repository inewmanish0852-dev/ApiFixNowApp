<?php
// =====================================================
// FILE: routes/admin.php
// =====================================================

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\DisputeController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AuthController;

// ── Login (middleware se bahar) ───────────────────────────────────────────────
Route::get ('login',  [LoginController::class, 'showLogin'])->name('login');
Route::post('login',  [LoginController::class, 'login'])->name('login.post');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// ── Protected Routes ──────────────────────────────────────────────────────────
Route::middleware('admin.auth')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Users / Customers
    Route::get   ('users',              [UserController::class, 'index'])  ->name('users.index');
    Route::get   ('users/{user}',       [UserController::class, 'show'])   ->name('users.show');
    Route::patch ('users/{user}/ban',   [UserController::class, 'ban'])    ->name('users.ban');
    Route::patch ('users/{user}/unban', [UserController::class, 'unban'])  ->name('users.unban');
    Route::delete('users/{user}',       [UserController::class, 'destroy'])->name('users.destroy');

    // Providers
    Route::get   ('providers',                     [ProviderController::class, 'index'])    ->name('providers.index');
    Route::get   ('providers/pending',             [ProviderController::class, 'pending'])  ->name('providers.pending');
    Route::get   ('providers/{provider}',          [ProviderController::class, 'show'])     ->name('providers.show');
    Route::patch ('providers/{provider}/verify',   [ProviderController::class, 'verify'])   ->name('providers.verify');
    Route::patch ('providers/{provider}/unverify', [ProviderController::class, 'unverify']) ->name('providers.unverify');
    Route::patch ('providers/{provider}/reject',   [ProviderController::class, 'reject'])   ->name('providers.reject');
    Route::patch ('providers/{provider}/suspend',  [ProviderController::class, 'suspend'])  ->name('providers.suspend');
    Route::patch ('providers/{provider}/unsuspend',[ProviderController::class, 'unsuspend'])->name('providers.unsuspend');

    // Bookings
    Route::get   ('bookings',                  [BookingController::class, 'index'])  ->name('bookings.index');
    Route::get   ('bookings/{booking}',        [BookingController::class, 'show'])   ->name('bookings.show');
    Route::patch ('bookings/{booking}/cancel', [BookingController::class, 'cancel']) ->name('bookings.cancel');

    // Services
    Route::get   ('services',                [ServiceController::class, 'index'])   ->name('services.index');
    Route::get   ('services/create',         [ServiceController::class, 'create'])  ->name('services.create');
    Route::post  ('services',                [ServiceController::class, 'store'])   ->name('services.store');
    Route::get   ('services/{service}/edit', [ServiceController::class, 'edit'])    ->name('services.edit');
    Route::put   ('services/{service}',      [ServiceController::class, 'update'])  ->name('services.update');
    Route::delete('services/{service}',      [ServiceController::class, 'destroy']) ->name('services.destroy');

    // Categories
    Route::get   ('categories',           [ServiceController::class, 'categoriesIndex'])   ->name('categories.index');
    Route::post  ('categories',           [ServiceController::class, 'categoriesStore'])   ->name('categories.store');
    Route::put   ('categories/{cat}',     [ServiceController::class, 'categoriesUpdate'])  ->name('categories.update');
    Route::delete('categories/{cat}',     [ServiceController::class, 'categoriesDestroy']) ->name('categories.destroy');

    // Reviews
    Route::get   ('reviews',                  [ReviewController::class, 'index'])   ->name('reviews.index');
    Route::patch ('reviews/{review}/flag',    [ReviewController::class, 'flag'])    ->name('reviews.flag');
    Route::patch ('reviews/{review}/approve', [ReviewController::class, 'approve']) ->name('reviews.approve');
    Route::delete('reviews/{review}',         [ReviewController::class, 'destroy']) ->name('reviews.destroy');

    // Disputes
    Route::get   ('disputes',                   [DisputeController::class, 'index'])   ->name('disputes.index');
    Route::get   ('disputes/{dispute}',         [DisputeController::class, 'show'])    ->name('disputes.show');
    Route::patch ('disputes/{dispute}/resolve', [DisputeController::class, 'resolve']) ->name('disputes.resolve');
    Route::patch ('disputes/{dispute}/close',   [DisputeController::class, 'close'])   ->name('disputes.close');

    // Notifications
    Route::get ('notifications',       [NotificationController::class, 'index']) ->name('notifications.index');
    Route::post('notifications/send',  [NotificationController::class, 'send'])  ->name('notifications.send');

    // Settings
    Route::get ('settings', [SettingsController::class, 'index'])  ->name('settings');
    Route::post('settings', [SettingsController::class, 'update']) ->name('settings.update');
});