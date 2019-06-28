<?php


namespace App\Transformers;


use App\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class UserTransformer extends Transformer

{
    public function simpleTransform(Model $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
        ];
    }
}