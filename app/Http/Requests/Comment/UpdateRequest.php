<?php


namespace App\Http\Requests\Comment;


use App\Http\Requests\DataPersistRequest;

class UpdateRequest extends DataPersistRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'body' => 'required|max:20',
        ];
    }

    public function persist(): self
    {
        $this->comment->update($this->getProcessedData());

        return $this;
    }

}