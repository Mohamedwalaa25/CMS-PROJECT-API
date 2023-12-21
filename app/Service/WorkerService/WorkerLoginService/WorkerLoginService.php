<?php

namespace App\Service\WorkerService\WorkerLoginService;

use App\Models\Worker;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;

class WorkerLoginService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Worker;
    }

    function validation($request)
    {

        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        return $validator;
    }

    function isValidData($data)
    {

        if (!$token = auth()->guard('worker')->attempt($data->validated())) {
            return response()->json(['error' => 'invalid data'], 401);
        }
        return $token;
    }

    function getStatus($email)
    {
        $Workerdata = $this->model->whereEmail($email)->first();
        $status = $Workerdata->status;
        return $status;
    }

    function isVerified($email)
    {
        $worker = $this->model->whereEmail($email)->first();
        $verified_at = $worker->verified_at;
        return $verified_at;
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->guard('worker')->user()
        ]);
    }

    function login($request)
    {
        $data = $this->validation($request);
        $token = $this->isValidData($data);

        if ($this->isVerified($request->email) == null) {
            return response()->json(["message" => "Your Acounnt is Not Verified"], 422);
        } else if ($this->getStatus($request->email) == 0) {

            return response()->json(["message" => "Your Acounnt is Pending"], 422);
        }
        return $this->createNewToken($token);

    }
}
