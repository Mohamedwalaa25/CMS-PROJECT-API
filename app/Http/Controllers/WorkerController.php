<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Admin;
use App\Models\Worker;
use App\Service\WorkerService\WorkerLoginService\WorkerLoginService;
use App\Service\WorkerService\WorkerRegisterService\WorkerRegisterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class WorkerController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:worker', ['except' => ['login', 'register', 'verify']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        return (new WorkerLoginService())->login($request);

    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        return (new WorkerRegisterService())->register($request);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify($token)
    {
        $worker = Worker::where('verification_token', $token)->first();
        if (!$worker) {
            return response()->json([
                "message" => "this is tokenis invalid"
            ]);
        }
        $worker->verification_token = null;
        $worker->save();
        return response()->json([
            "message" => "Your Account has been Verified"
        ]);
    }

    public function logout()
    {
        auth()->guard('worker')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth()->guard('worker')->user());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->guard('worker')->user()
        ]);
    }
}
