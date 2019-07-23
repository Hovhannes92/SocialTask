<?php

namespace App\Http\Controllers\Api;

use App\Chat;
use App\Comment;
use App\Http\Requests\Chat\AddUserRequest;
use App\Http\Requests\Chat\ChatCheckRequest;
use App\Http\Requests\Chat\IndexRequest;
use App\Http\Requests\Chat\ShowRequest;
use App\Message;
use App\Transformers\ChatTransformer;
use App\Transformers\MessageTransformer;
use App\Transformers\PostTransformer;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IndexRequest $request)
    {
        return response()->json(ChatTransformer::collection($request->persist()->chat,'detailedTransform')) ;
//        return $request->persist()->chat;
    }

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
//    public function store(Request $request)
//    {
//
//    }

    public function chat_check(ChatCheckRequest $request, User $user, Message $message)
    {
        return response()->json(ChatTransformer::simple($request->persist()->chat));
    }

    public function add_user(AddUserRequest $request, Chat $chat)
    {
        return response()->json(ChatTransformer::simple($request->persist()->chat));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request, Chat $chat)
    {
        $request->persist();
    }
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
//    public function update(Request $request, $id)
//    {
//        //
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function destroy($id)
//    {
//        //
//    }
}
