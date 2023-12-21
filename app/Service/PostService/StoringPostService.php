<?php

namespace App\Service\PostService;

use App\Models\Admin;
use App\Models\Post;
use App\Models\PostPhoto;
use App\Notifications\AdminPost;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;
use Illuminate\Support\Facades\Notification;


class StoringPostService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Post();
    }

    /**
     * @return Post
     */
    public function AdminPercent($price)
    {
        $discount = $price * 0.05;
        $priceAfterdDiscount = $price - $discount;
        return $priceAfterdDiscount;
    }

    public function storePost($data)
    {
        $data = $data->except('photos');
        $data["price"]=$this->AdminPercent( $data["price"]);
        $data["worker_id"] = auth('worker')->id();
        $post = Post::create($data);
        return $post;
    }

    public function StorePostPhoto($request, $postId)
    {
        foreach ($request->file('photos') as $photo) {
            $postPhotos = PostPhoto::class;
            $postPhotos->post_id = $postId;
            $postPhotos->photos = $photo->store("posts");
            $postPhotos->save();
        }
    }

    function sendAdminNotification($post)
    {
        $admins = Admin::get();
        Notification::send($admins, new AdminPost(auth('worker')->user(), $post));
    }

    function store($request)
    {
        try {
            DB::beginTransaction();
            $post = $this->storePost($request);
            if ($request->hasFile('photos')) {
                $postPhoto = $this->StorePostPhoto($request, $post->id);
            }
            $this->sendAdminNotification($post);
            DB::commit();
            return response()->json([
                "message" => "Your Post Has Created Successfuly ,Your Price After Discount is {$post->price}"
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}

