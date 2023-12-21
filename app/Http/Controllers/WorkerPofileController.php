<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatingProfileServiceRequest;
use App\Models\Worker;
use App\Models\Worker_Reviews;
use App\Service\WorkerService\UpdatingProfileService\UpdatingProfileService;
use Illuminate\Http\Request;

class WorkerPofileController extends Controller
{
    public function userProfile()
    {
        $workerId = auth('worker')->id();
        $worker = Worker::with('posts.review')->find($workerId);
        $review = Worker_Reviews::wherein("post_id", $worker->posts()->pluck('id'));
        $rate = round($review->sum('rate') / $review->count(), 1);
        return response()->json([
            "data" => array_merge($worker->toArray(), ['rate' => $rate])
        ]);
    }

    public function edit()
    {
        return response()->json([
            "worker" => auth('worker')->user()
        ]);
    }
    public function update(UpdatingProfileServiceRequest $request)
    {
      return (new UpdatingProfileService())->update($request);
    }

}
