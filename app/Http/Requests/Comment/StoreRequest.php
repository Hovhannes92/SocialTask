<?php


namespace App\Http\Requests\Comment;


use App\Http\Requests\DataPersistRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends DataPersistRequest
{
    public $comment;

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
        $this->comment = Auth::user()->comments()->create(array_merge([
            'post_id' => $this->post->id,
            ],
            $this->getProcessedData()
        ));

        return $this;
    }

}