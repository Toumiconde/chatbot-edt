<?php

namespace App\Http\Controllers;

use App\Models\NotificationApp;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // GET /api/notifications — liste + compteur pour l'utilisateur connecté
    public function index()
    {
        $user = auth()->user();
        if (!$user) return response()->json(['notifications' => [], 'unread_count' => 0]);

        $notifications = NotificationApp::forUser($user->id)
            ->orderByDesc('created_at')
            ->take(20)
            ->get()
            ->map(fn($n) => [
                'id'      => $n->id,
                'type'    => $n->type,
                'titre'   => $n->titre,
                'message' => $n->message,
                'unread'  => $n->isUnread(),
                'date'    => $n->created_at->diffForHumans(),
                'data'    => $n->data,
            ]);

        $unreadCount = NotificationApp::forUser($user->id)->unread()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
        ]);
    }

    // PUT /api/notifications/read-all — tout marquer comme lu
    public function readAll()
    {
        $user = auth()->user();
        if (!$user) return response()->json(['ok' => false], 401);

        NotificationApp::forUser($user->id)->unread()->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }

    // PUT /api/notifications/{id}/read — marquer une seule notification comme lue
    public function read(int $id)
    {
        $user = auth()->user();
        if (!$user) return response()->json(['ok' => false], 401);

        $notif = NotificationApp::where('user_id', $user->id)->findOrFail($id);
        $notif->markAsRead();

        return response()->json(['ok' => true]);
    }
}
