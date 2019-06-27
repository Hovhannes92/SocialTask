<?php


namespace App\Http\Requests\Post;


use App\Http\Requests\ApiRequest;

class ShowRequest extends ApiRequest
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

}