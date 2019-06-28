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
        ];
    }
}