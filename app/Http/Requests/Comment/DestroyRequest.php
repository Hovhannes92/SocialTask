<?php


namespace App\Http\Requests\Comment;


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
        $this->comment->delete();

        return $this;
    }

    public function getMessage(): string
    {
        return "Comment successfully deleted.";
    }


}