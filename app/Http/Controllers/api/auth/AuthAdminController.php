<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthAdminController extends Controller
{

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $admin = $request->validated();

        if(Auth::guard("admin")->attempt($admin)){
            $admin = Auth::guard("admin")->user();

            $access_token = $admin->createToken("admin_".$request->userAgent() , ["admin"]);

            return response()->json([
                'access_token' => $access_token->plainTextToken,
                'token' => $admin->tokens()->get("name", "abilities"),
                'admin' => $admin,]);

        }
        return  response()->json(["msg"=>"Invalid"]);
    }

    public function register(RegisterRequest $request)
    {

        $user = Admin::create([
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
        $personalleAccessToken = PersonalAccessToken::findToken( $request->bearerToken());

        if($personalleAccessToken->tokenable_type === "App\\Models\\Admin"
            &&
            $personalleAccessToken->tokenable_id === $user->id) {
            $user->tokens()->delete();
            return response()->json(["msg"=>"Logout successful"]);
        }else{
            return  response()->json(["msg"=>"Access denied"]);
        }

        return response()->json(["msg"=>"Logout failed"]);
    }
}
