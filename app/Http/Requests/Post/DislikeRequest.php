<?php


namespace App\Http\Requests\Post;


use App\Http\Requests\DataPersistRequest;
use App\Like;
use Illuminate\Support\Facades\Auth;

class DislikeRequest extends DataPersistRequest
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

        $this->post->likes()->create(array_merge([
            'user_id' => Auth::user()->id,
            'like_dislike' => Like::LIKE_DISLIKE['dislike'],
        ],
            $this->getProcessedData()
        ));

        return $this;
    }

    public function getMessage(): string
    {
        return "Your Post has been successfully disliked.";
    }


}