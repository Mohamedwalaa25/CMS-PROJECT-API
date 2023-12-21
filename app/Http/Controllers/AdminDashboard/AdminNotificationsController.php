<?php

namespace App\Http\Controllers\AdminDashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

class AdminNotificationsController extends Controller
{
    function index()
    {
        $admin = Admin::Find(1);
        return response()->json([
            "Notifications" => $admin->notifications

        ]);
    }

    function unread()
    {
        $admin = Admin::Find(1);
        return response()->json([
            "Notifications" => $admin->unreadNotifications

        ]);
    }

    function readall()
    {
        $admin = Admin::Find(1);
        foreach ($admin->unreadNotifications as $notification) {
            $notification->markAsRead();


            return response()->json([
                "message" => "Success"

            ]);
        }
    }
      function deleteAll()
        {
            $admin = Admin::Find(1);
            $admin->notifications()->delete();
            return response()->json([
                "message" => "Success"
            ]);
        }

        function deleteNoti($id)
        {
            DB::table('notifications')->where('id', $id)->delete();
            return response()->json([
                "message" => "Delete"

            ]);
        }

}
