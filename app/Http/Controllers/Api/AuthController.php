<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        $request->loginAttempt();

        return response()->json(UserTransformer::simple(Auth::user()));
    }


    public function register(RegisterRequest $request)
    {
         return response()->json($request->persist()->user);
    }

}