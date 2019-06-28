<?php

namespace App\Http\Controllers\Api;

use App\Comment;
use App\Http\Requests\Post\DestroyRequest;
use App\Http\Requests\Post\IndexRequest;
use App\Http\Requests\Post\ShowRequest;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Post;
use App\Transformers\PostTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
          return response()->json(PostTransformer::collection(Post::all(),'simpleTransform'))  ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function store(StoreRequest $request)
    {
        return response()->json(PostTransformer::simple($request->persist()->post));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request, Post $post)
    {
        return response()->json(PostTransformer::simple($post));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(UpdateRequest $request, Post $post)
    {
        return response()->json(PostTransformer::simple($request->persist()->post));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request, Post $post)
    {
        return response()->json($request->persist()->getResponseMessage());
    }
}
