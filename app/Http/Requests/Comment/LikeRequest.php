<?php


namespace App\Http\Requests\Comment;

use App\Http\Requests\DataPersistRequest;
use App\Like;
use Illuminate\Support\Facades\Auth;

class LikeRequest extends DataPersistRequest
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

    protected function getMergingData(): array
    {
        return ['user_id' => Auth::user()->id,
            'like_dislike' => Like::LIKE_DISLIKE['like'],];
    }

    public function persist(): self
    {
        $this->comment->likes()->create($this->getProcessedData());

        return $this;
    }

    public function getMessage(): string
    {
        return "Your Comment has been successfully liked.";
    }


}