<?php


namespace App\Transformers;

use App\Comment;
use App\Like;
use Illuminate\Database\Eloquent\Model;

class PostTransformer extends Transformer

{

    public function simpleTransform(Model $post): array
    {
            return [
              'id' => $post->id,
              'title' => $post->title,
              'user_name' => $post->user->name,
                'post_view_count' => $post->views()->count(),
                'post_like_count' => $post->likes()->where('like_dislike', 1)->count(),
                'post_dislike_count' => $post->likes()->where('like_dislike', 2)->count(),
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