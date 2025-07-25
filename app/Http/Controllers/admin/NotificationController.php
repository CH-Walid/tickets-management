<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->delete();
        return response()->json(['success' => true]);
    }
} 