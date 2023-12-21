<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\StorePostRequest;
use App\Models\Post;
use App\Service\PostService\StoringPostService;
use Spatie\QueryBuilder\QueryBuilder;

class PostController extends Controller
{

    public function index()
    {

        $posts = Post::all();
        return response()->json([
            "posts" => $posts
        ]);
    }

    public function store(StorePostRequest $request)
    {
        return (new StoringPostService())->store($request);

    }

    public function approved()
    {
        $posts = QueryBuilder::for(Post::class)
        ->allowedFilters(['price',"content","worker.name"])
            ->with("worker:id,name")
            ->where('status','approved')
        ->get();

     //   $posts = Post::with("worker:id,name")->where('status','approved')->get();
        return response()->json([
            "posts" => $posts
        ]);

    }
}
