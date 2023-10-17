<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;

class AdminNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admin = Admin::findOrFail(auth()->guard('admin')->user()->id);
        return new JsonResponse([
            'notifications' => $admin->notifications
        ]);
    }

    public function unread()
    {
        $admin = Admin::findOrFail(auth()->guard('admin')->user()->id);
        return new JsonResponse([
            'notifications' => $admin->unreadNotifications
        ]);
    }

    public function markRead()
    {
        $admin = Admin::findOrFail(auth()->guard('admin')->user()->id);
        $admin->unreadNotifications()->update(['read_at' => now()]);

        return new JsonResponse([
            'message' => 'Read notifications'
        ]);
    }

    public function delete(Request $request)
    {
        DB::table('notifications')->where('id', $request->id)->delete();
        return new JsonResponse([
            'message' => 'Notification deleted',
        ]);
    }

    public function deleteAll()
    {
        $admin = Admin::findOrFail(auth()->guard('admin')->user()->id);
        $admin->notifications()->delete();

        return new JsonResponse([
            'message' => 'Notifications Have Deleted Successfully'
        ]);
    }
}
