<?php


namespace App\Http\Requests\Comment;


use App\Http\Requests\ApiRequest;

class IndexRequest extends ApiRequest
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