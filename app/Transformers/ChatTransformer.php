<?php


namespace App\Transformers;


use Illuminate\Database\Eloquent\Model;

class ChatTransformer extends Transformer
{

    public function simpleTransform(Model $chat): array
    {
        return [
            'id' => $chat->id,
            'type' => $chat->type,
        ];
    }

}
