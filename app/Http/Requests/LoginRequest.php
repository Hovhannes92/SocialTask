<?php


namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class LoginRequest extends ApiRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function loginAttempt()
    {

        $credentials = [
            'email' => $this['email'],
            'password' => $this['password'],
        ];

        abort_if(!Auth::attempt($credentials), 403, 'Unauthorized.');

        Auth::user()->update(['api_token' => str_random(50)]);
    }

}