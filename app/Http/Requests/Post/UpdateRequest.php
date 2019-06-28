<?php


namespace App\Http\Requests\Post;


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
            'title' => 'required|max:20',
            'subtitle' => 'required|max:20',
            'description' => 'required|max:200',
        ];
    }

    public function persist(): self
    {
        $this->post->update($this->getProcessedData());

        return $this;
    }
}