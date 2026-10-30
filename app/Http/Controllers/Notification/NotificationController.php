<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use App\Services\Notification\NotificationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
// use Illuminate\Http\Request;
use Throwable;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Menampilkan daftar notifikasi.
     */
    public function index()
    {
        try {
            $data = $this->notificationService->getNotifications();

            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'Data notifikasi berhasil ditemukan.',
                'data' => $data,
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Gagal mengambil data notifikasi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menghitung notifikasi yang belum dibuka.
     */
    public function unreadCount()
    {
        try {
            $count = $this->notificationService->getUnreadCount();

            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'Jumlah notifikasi berhasil ditemukan.',
                'data' => [
                    'unread_count' => $count,
                ],
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Gagal menghitung notifikasi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menandai satu notifikasi sebagai sudah dibuka.
     */
    public function markAsRead(int $id)
    {
        try {
            $this->notificationService->markAsRead((int) $id);

            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'Notifikasi berhasil dibuka.',
                'data' => null,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan.',
            ], 404);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Gagal membuka notifikasi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Menandai semua notifikasi sebagai sudah dibuka.
     */
    public function markAllAsRead()
    {
        try {
            $this->notificationService->markAllAsRead();

            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'Semua notifikasi berhasil dibuka.',
                'data' => null,
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Gagal membuka semua notifikasi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
