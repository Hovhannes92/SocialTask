<?php


namespace App\Transformers;

use Illuminate\Database\Eloquent\Model;

class TagTransformer extends Transformer
{
    public function simpleTransform(Model $tag): array
    {
        return [
            'tag_word' => $tag->tag_word,
        ];
    }

}