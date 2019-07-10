<?php


namespace App\Transformers;

use Illuminate\Database\Eloquent\Model;

class MessageTransformer extends Transformer
{

    public function simpleTransform(Model $message): array
    {
        return [
            'message' => $message->message,
            'user_name' => $message->user->name,
        ];
    }

}