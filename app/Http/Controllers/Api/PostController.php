<?php

namespace App\Http\Controllers\Api;

use App\Comment;
use App\Http\Requests\Post\DestroyRequest;
use App\Http\Requests\Post\DislikeRequest;
use App\Http\Requests\Post\IndexRequest;
use App\Http\Requests\Post\LikeRequest;
use App\Http\Requests\Post\ShowRequest;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\TagRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Post;
use App\Transformers\PostTransformer;
use App\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('post_view')->only('show');
    }

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

        dd($post->tags->find(1)->tag_word);

//        if ($request->post->views()->where('user_id', Auth::user()->id)->count() === 0  ||
//          ((strtotime(Carbon::now()) - (strtotime((View::orderBy('created_at', 'desc')->where('user_id', Auth::user()->id)
//               ->first()->created_at)))) > 3600))
//        {
//            $request->persist();
//        }
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

    public function like(LikeRequest $request, Post $post)
    {
//        dd(Auth::user()->roles->first()->name);

        return response()->json($request->persist()->getResponseMessage());
    }

    public function dislike(DislikeRequest $request, Post $post)
    {
        return response()->json($request->persist()->getResponseMessage());
    }

}
