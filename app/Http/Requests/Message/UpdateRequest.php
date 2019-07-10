<?php


namespace App\Http\Requests\Message;


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
            'message' => 'required|max:250',
        ];
    }

    public function persist(): self
    {
        $this->message->update($this->getProcessedData());

        return $this;
    }

}