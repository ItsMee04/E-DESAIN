<?php

namespace App\Services\Notification;

use App\Models\Notification\Notification;
use App\Models\Notification\NotificationRead;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Membuat notifikasi global.
     */
    public function createNotification(array $data): Notification
    {
        return Notification::create([
            'pengajuan_id' => $data['pengajuan_id'] ?? null,
            'title'        => $data['title'],
            'message'      => $data['message'],
            'type'         => $data['type'] ?? null,
        ]);
    }

    /**
     * Mengambil seluruh notifikasi beserta status pembacaan user.
     */
    public function getNotifications()
    {
        $userId = Auth::id();

        return Notification::query()
            ->withExists([
                'reads as is_read' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }
            ])
            ->latest()
            ->paginate(10);
    }

    /**
     * Menghitung notifikasi yang belum dibuka user.
     */
    public function getUnreadCount(): int
    {
        $userId = Auth::id();

        return Notification::whereDoesntHave('reads', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->count();
    }

    /**
     * Menandai satu notifikasi sebagai sudah dibuka.
     */
    public function markAsRead(int $id): NotificationRead
    {
        $notification = Notification::findOrFail($id);

        return NotificationRead::firstOrCreate(
            [
                'notification_id' => $notification->id,
                'user_id' => Auth::id(),
            ],
            [
                'read_at' => now(),
            ]
        );
    }

    /**
     * Menandai seluruh notifikasi sebagai sudah dibuka.
     */
    public function markAllAsRead(): void
    {
        $userId = Auth::id();

        $notifications = Notification::query()
            ->whereDoesntHave('reads', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->pluck('id');

        $now = now();

        $data = $notifications->map(function ($notificationId) use ($userId, $now) {
            return [
                'notification_id' => $notificationId,
                'user_id' => $userId,
                'read_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->all();

        if (!empty($data)) {
            NotificationRead::insert($data);
        }
    }
}
