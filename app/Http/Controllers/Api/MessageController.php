<?php

namespace App\Http\Controllers\Api;

use App\Chat;
use App\Comment;
use App\Http\Requests\Message\DestroyRequest;
use App\Http\Requests\Message\IndexRequest;
use App\Http\Requests\Message\StoreRequest;
use App\Http\Requests\Message\UpdateRequest;
use App\Message;
use App\Transformers\CommentTransformer;
use App\Transformers\MessageTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        return response()->json(MessageTransformer::collection(Message::all(),'simpleTransform')) ;
    }
//
//    /**
//     * Show the form for creating a new resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function create()
//    {
//        //
//    }
//
//    /**
//     * Store a newly created resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\Response
//     */
    public function store(StoreRequest $request, Message $message, Chat $chat)
    {
        $request->persist();
        return response()->json(MessageTransformer::collection(Message::all(),'simpleTransform'));
    }

//    /**
//     * Display the specified resource.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function show($id)
//    {
//        //
//    }
//
//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function edit($id)
//    {
//        //
//    }
//
//    /**
//     * Update the specified resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
    public function update(UpdateRequest $request, Message $message)
    {
        $request->persist();
        return response()->json(MessageTransformer::collection(Message::all(),'simpleTransform'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyRequest $request ,Message $message)
    {
        return response()->json($request->persist()->getResponseMessage());
    }
}
