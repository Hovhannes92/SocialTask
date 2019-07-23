<?php

namespace App\Http\Controllers\Api;

use App\Comment;
use App\Http\Requests\Comment\DestroyRequest;
use App\Http\Requests\Comment\DislikeRequest;
use App\Http\Requests\Comment\IndexRequest;
use App\Http\Requests\Comment\LikeRequest;
use App\Http\Requests\Comment\ShowRequest;
use App\Http\Requests\Comment\StoreRequest;
use App\Http\Requests\Comment\UpdateRequest;
use App\Post;
use App\Transformers\CommentTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        return response()->json(CommentTransformer::collection(Comment::all(),'simpleTransform')) ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Post $post)
    {
        return response()->json(CommentTransformer::simple($request->persist()->comment));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request,Post $post, Comment $comment)
    {
        return response()->json(CommentTransformer::simple($comment));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Post $post, Comment $comment)
    {
        return response()->json(CommentTransformer::simple($request->persist()->comment));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request, Post $post, Comment $comment)
    {
        return response()->json($request->persist()->getResponseMessage());
    }

    public function like(LikeRequest $request, Post $post, Comment $comment)
    {
        return response()->json($request->persist()->getResponseMessage());
    }

    public function dislike(DislikeRequest $request, Post $post, Comment $comment)
    {
        return response()->json($request->persist()->getResponseMessage());
    }


}
