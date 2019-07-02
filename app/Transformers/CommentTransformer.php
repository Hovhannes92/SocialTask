<?php


namespace App\Transformers;


use Illuminate\Database\Eloquent\Model;

class CommentTransformer extends Transformer
{
    public function simpleTransform(Model $comment): array
    {
        return [
            'id' => $comment->id,
            'body' => $comment->body,
            'comment_like_count' => $comment->likes()->where('like_dislike', 1)->count(),
            'comment_dislike_count' => $comment->likes()->where('like_dislike', 2)->count(),
        ];
    }
}