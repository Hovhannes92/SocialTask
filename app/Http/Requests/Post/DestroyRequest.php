<?php


namespace App\Http\Requests\Post;


use App\Http\Requests\DataPersistRequest;

class DestroyRequest extends DataPersistRequest
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

    public function persist(): self
    {
        $this->post->delete();

        return $this;
    }

    public function getMessage(): string
    {
        return "Category successfully deleted.";
    }

}