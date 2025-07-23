<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class MessageController extends Controller
{
    // texts between tech and admin (select)
    public function index()
    {
        $userId = Auth::id();
        $chef = User::whereHas('chefTechnicien')->first();
        if (!$chef) return response()->json([]);
        $messages = Message::where(function($q) use ($userId, $chef) {
            $q->where('from_user_id', $userId)->where('to_user_id', $chef->id);
        })->orWhere(function($q) use ($userId, $chef) {
            $q->where('from_user_id', $chef->id)->where('to_user_id', $userId);
        })->orderBy('created_at', 'asc')->get();
        return response()->json($messages);
    }

    // send a text
    public function store(Request $request)
    {
        $request->validate(['content' => 'required|string']);
        $userId = Auth::id();
        $chef = User::whereHas('chefTechnicien')->first();
        if (!$chef) return response()->json(['error' => 'Chef technicien non trouvé'], 404);
        $message = Message::create([
            'from_user_id' => $userId,
            'to_user_id' => $chef->id,
            'content' => $request->content,
        ]);
        return response()->json($message);
    }

    // mark as read
    public function markAsRead($id)
    {
        $message = Message::where('to_user_id', Auth::id())->findOrFail($id);
        $message->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }
} 