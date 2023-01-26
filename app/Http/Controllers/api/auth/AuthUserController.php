<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthUserController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $user = $request->validated();

        if(Auth::attempt($user)){
            $user = Auth::user();
            $access_token = $user->createToken($request->userAgent());

            return response()->json([
                'access_token' => $access_token->plainTextToken,
                'token' => $user->tokens()->get("name"),
                'user' => $user,]);

        }

        return  response()->json(["msg"=>"Invalid"]);
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {

        $user = User::create([
            "first_name"=> $request->first_name,
            "last_name"=> $request->last_name,
            "email"=> $request->email,
            "phone"=> $request->phone,
            "password"=> Hash::make($request->password),
        ]);

        if($user){
            return response()->json([
                "msg"=>"Register successful"
            ]);
        }
       return response()->json(["msg"=>"Register failed"]);
    }


    public function logout(Request $request)
    {
        $user = Auth::guard("sanctum")->user();

        if($user) {
            $user->tokens()->delete();
            return response()->json(["msg"=>"Logout successful"]);
        }

        return response()->json(["msg"=>"Logout failed"]);
    }
}
