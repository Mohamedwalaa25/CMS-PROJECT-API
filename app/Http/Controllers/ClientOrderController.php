<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\ClientServicesRequest;
use App\Interfaces\CrudRepoInterface;
use App\Models\ClientOrder;
use Illuminate\Http\Request;

class ClientOrderController extends Controller
{
    protected $repo;

    public function __construct(CrudRepoInterface $repo)
    {
        $this->repo = $repo;
    }

    public function addorder(ClientServicesRequest $request)
    {
        return $this->repo->store($request);
    }

    public function workerorder()
    {
        $order = ClientOrder::with("post:id,content", "client:id,name")->where("status", "pending")
            ->whereHas('post', function ($query) {
                $query->where("worker_id", auth('worker')->id());
            })
            ->get();
        return response()->json([
            "orders" => $order
        ]);
    }

    public function update($id, Request $request)
    {
        $order=ClientOrder::findOrFail($id);
        $order->setAttribute('status',$request->status)->save();
        return response()->json([
            "message"=>"Updated"
        ]);

    }

}
