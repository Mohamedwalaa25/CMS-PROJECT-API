<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkerReviewsRequest;
use App\Http\Resources\WorkerreviewResourse;
use App\Models\Worker_Reviews;
use Illuminate\Http\Request;

class WorkerReviewsController extends Controller
{
    public function store(WorkerReviewsRequest $request )
    {
       $data =$request->all();
       $data['client_id']=auth("client")->id();
       $reviews = Worker_Reviews::create($data);
       return response()->json([
           "data"=>$reviews
       ]);
    }
    public function postrate($id){
        $reviews =Worker_Reviews::query()->where("post_id",$id);
        $average=$reviews->sum('rate')/$reviews->count();
        return response()->json([
            "total_rate"=>round($average,1),
            "data"=>WorkerreviewResourse::collection($reviews->get())
        ]);

    }
}
