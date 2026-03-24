<?php
// =====================================================
// FILE: app/Http/Controllers/Admin/NotificationController.php
// =====================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')->latest()->paginate(30);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'body'    => 'required|string',
            'type'    => 'required|in:booking,provider,promo,system,general',
            'send_to' => 'required|in:all,customers,providers,specific',
            'user_id' => 'nullable|required_if:send_to,specific|exists:users,id',
        ]);

        Notification::create([
            'user_id' => $request->send_to === 'specific' ? $request->user_id : null,
            'title'   => $request->title,
            'body'    => $request->body,
            'type'    => $request->type,
            'icon'    => match($request->type) {
                'booking'  => '📅',
                'provider' => '🔧',
                'promo'    => '🎁',
                'system'   => '⚙️',
                default    => '🔔',
            },
        ]);

        return back()->with('success', 'Notification sent successfully!');
    }
}