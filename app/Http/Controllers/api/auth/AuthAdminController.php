<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthAdminController extends Controller
{

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $admin = $request->validated();

        return  Auth::guard("admin");

        if(Auth::guard("admin")->attempt($admin)){

            return  response()->json([
                "msg"=>"admin"
            ]);

        }

        return  response()->json(["msg"=>"Invalid"]);
    }

}
