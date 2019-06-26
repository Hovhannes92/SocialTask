<?php


namespace App\Transformers;

use App\Post;
use Illuminate\Database\Eloquent\Model;

class PostTransformer extends Transformer
{
    public function simpleTransform(Model $post): array
    {
        return [
            'id'            => $post->id,
            'title'          => $post->title,
            'subtitle'         => $post->subtitle,
            'description'    => $post->description,
//            'user_id'          => $post->user,
            'created_at'    => $this->transformDate($post->created_at),
            'updated_at'    => $this->transformDate($post->updated_at),
        ];
    }
}