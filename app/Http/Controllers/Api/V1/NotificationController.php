<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Traits\ApiResponse;

class NotificationController extends Controller
{
    use ApiResponse;
    public function index()
    {
        $notifs = Notification::where('user_id', auth()->id())
            ->latest()->paginate(20);
        return $this->paginated($notifs, 'Notifications fetched.');
    }

    public function markRead($id)
    {
        Notification::where('user_id', auth()->id())->where('id', $id)
            ->update(['is_read' => true]);
        return $this->success(null, 'Marked as read.');
    }

    public function markAllRead()
    {
        Notification::where('user_id', auth()->id())->update(['is_read' => true]);
        return $this->success(null, 'All marked as read.');
    }

    public function unreadCount()
    {
        $count = Notification::where('user_id', auth()->id())
            ->where('is_read', false)->count();
        return $this->success(['count' => $count]);
    }
}