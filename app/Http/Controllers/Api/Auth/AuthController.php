<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ApiTrait;
    public function register(RegisterRequest $request)
    {
        $data=$request->validated();
        $data['password']=Hash::make($data['password']);
        $user = User::create($data);
        $token = JWTAuth::fromUser($user);

        return $this->successResponse(['user'=>new UserResource($user), 'token'=>$token],'new user created success', 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->errorResponse('login credentials not accepted', 401);
        }
        return $this->respondWithToken($token,'login success, welcome back!');
    }

    public function logout()
    {
        auth()->logout();
        return $this->successResponse([], 'Successfully logged out');
    }

    public function refresh()
    {

        return $this->respondWithToken(auth('api')->refresh(),'token refresh success');
    }

    protected function respondWithToken($token,$message='')
    {
        return $this->successResponse([
            'user'                  => new UserResource(auth()->user()),
            'access_token'          => $token,
            'token_type'            => 'bearer',
            'expires_in_seconds'    => auth('api')->factory()->getTTL() * 60
        ],$message);
    }

}
