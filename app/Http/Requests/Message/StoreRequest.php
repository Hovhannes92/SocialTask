<?php


namespace App\Http\Requests\Message;

use App\Http\Requests\DataPersistRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends DataPersistRequest
{

    public $message;

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

    protected function getMergingData(): array
    {
        return ['chat_id' => $this->chat->id];
    }

    public function persist(): self
    {
        $this->message = Auth::user()->messages()->create($this->getProcessedData());

        return $this;
    }

}
