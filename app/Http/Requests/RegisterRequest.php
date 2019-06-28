<?php


namespace App\Http\Requests;

use App\User;

class RegisterRequest extends DataPersistRequest
{
    public $user;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ];
    }

    public function persist(): self
    {
        $this->user = User::create($this->getProcessedData());

        return $this;
    }



}