<?php

namespace App\Repository;

use App\Interfaces\CrudRepoInterface;
use App\Models\ClientOrder;

class ClientOrderRepo implements CrudRepoInterface
{
    public function store($request)
    {
        $clientid = auth("client")->id();
        if (ClientOrder::where("client_id", $clientid)->where("post_id", $request->post_id)->exists()) {
            return response()->json([
                "message" => "Can't Duplicated Order"
            ]);
        }
        $data = $request->all();
        $data['client_id'] = $clientid;
        $order = ClientOrder::create($data);
        return response()->json([
            "message" => "successes"
        ]);
    }
}
