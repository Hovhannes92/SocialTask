<?php


namespace App\Transformers;

use App\Comment;
use App\Post;
use Illuminate\Database\Eloquent\Model;

class PostTransformer extends Transformer

{

    public function simpleTransform(Model $post): array
    {
            return [
              'id' => $post->id,
              'title' => $post->title,
              'user_name' => $post->user->name,
               'comment_body' => CommentTransformer::collection(Comment::where('post_id', $post->id)->get(), 'simpleTransform'),

            ];
    }

    public function detailedTransform(Model $post): array
    {
        return [
            'id' => $post->id,
        ];
    }

}