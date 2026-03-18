<?php
namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public static function send(int $userId, string $title, string $body,
                                 string $type = 'general', ?int $refId = null): void
    {
        Notification::create([
            'user_id' => $userId,
            'title'   => $title,
            'body'    => $body,
            'type'    => $type,
            'ref_id'  => $refId,
        ]);
    }

    public static function bookingCreated($booking): void
    {
        // Notify provider
        self::send(
            $booking->provider->user_id,
            'New Job Request!',
            "You have a new {$booking->service_type} booking request.",
            'booking', $booking->id
        );
    }

    public static function bookingAccepted($booking): void
    {
        self::send(
            $booking->customer_id,
            'Booking Confirmed!',
            "Your booking has been accepted by {$booking->provider->user->name}.",
            'booking', $booking->id
        );
    }

    public static function jobStatusUpdated($booking): void
    {
        $statusMsg = [
            'on_the_way'  => 'Your technician is on the way!',
            'in_progress' => 'Work has started at your location.',
            'completed'   => 'Your job has been completed.',
        ];

        $msg = $statusMsg[$booking->status] ?? "Booking status updated to {$booking->status}.";

        self::send($booking->customer_id, 'Booking Update', $msg, 'booking', $booking->id);
    }

    public static function newMessage($message, $receiverId): void
    {
        self::send($receiverId, 'New Message', $message->message, 'chat', $message->booking_id);
    }
}