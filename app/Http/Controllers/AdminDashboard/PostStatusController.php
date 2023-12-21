<?php

namespace App\Http\Controllers\AdminDashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostStatusRequest;
use App\Models\Post;
use App\Notifications\AdminPost;
use Illuminate\Support\Facades\Notification;

class PostStatusController extends Controller
{
    public function changestatus(PostStatusRequest $request){

        $post=Post::find($request->post_id);
        $post->update([
            "status"=>$request->status,
            "rejected_reason"=>$request->rejected_reason
        ]);
        Notification::send($post->worker ,new AdminPost($post->worker,$post));
        return response()->json([
            "message"=>"post status has been changed"
        ]);
    }
}
