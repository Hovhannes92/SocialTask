<?php


namespace App\Transformers;


use App\Post;
use Illuminate\Database\Eloquent\Model;

class PostTransformer extends Transformer

{

    public function simpleTransform(Model $post): array
    {
            return [
              'id' => $post->id,
              'title' => $post->title,
            ];
    }
}