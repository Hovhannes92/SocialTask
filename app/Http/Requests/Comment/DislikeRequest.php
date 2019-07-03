<?php


namespace App\Http\Requests\Comment;


use App\Http\Requests\DataPersistRequest;
use App\Like;
use Illuminate\Support\Facades\Auth;

class DislikeRequest extends DataPersistRequest
{

    public function authorize(): bool
    {
        return Auth::user()->can('dislike', $this->comment);
    }

    public function rules(): array
    {
        return [

        ];
    }

    protected function getMergingData(): array
    {
        return ['user_id' => Auth::user()->id,
            'like_dislike' => Like::LIKE_DISLIKE['dislike'],];
    }

    public function persist(): self
    {
        $this->comment->likes()->where('user_id', Auth::user()->id)->delete();
        $this->comment->likes()->create($this->getProcessedData());

        return $this;
    }

    public function getMessage(): string
    {
        return "Your Post has been successfully disliked.";
    }



}