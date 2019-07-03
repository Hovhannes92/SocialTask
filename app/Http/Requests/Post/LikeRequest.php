<?php


namespace App\Http\Requests\Post;


use App\Http\Requests\DataPersistRequest;
use App\Like;
use const http\Client\Curl\AUTH_ANY;
use Illuminate\Support\Facades\Auth;

class LikeRequest extends DataPersistRequest
{

    public function authorize(): bool
    {
        return Auth::user()->can('like', $this->post);
    }

    public function rules(): array
    {
        return [

        ];
    }

    protected function getMergingData(): array
    {
        return ['user_id' => Auth::user()->id,
            'like_dislike' => Like::LIKE_DISLIKE['like'],];
    }

    public function persist(): self
    {
//        if($this->post->likes()->where('user_id', Auth::user()->id && 'like_dislike', 2)->exists())
        $this->post->likes()->where('user_id', Auth::user()->id)->delete();
        $this->post->likes()->create($this->getProcessedData());

        return $this;
    }

    public function getMessage(): string
    {
        return "Your Post has been successfully liked.";
    }

}