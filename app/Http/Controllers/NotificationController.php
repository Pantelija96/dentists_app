<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead(Notification $notification)
    {
        $notification->update(['seen' => true]);
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            Notification::where('region_id', $user->region_id)
                ->where('seen', false)
                ->update(['seen' => true]);
        } else {
            Notification::where('user_id', $user->id)
                ->where('seen', false)
                ->update(['seen' => true]);
        }

        session([
            'notifications' => collect(),
            'notifications_count' => 0,
        ]);

        return back();
    }
}
