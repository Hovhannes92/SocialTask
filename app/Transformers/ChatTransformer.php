<?php


namespace App\Transformers;


use App\ChatUserAction;
use App\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ChatTransformer extends Transformer
{


    public function simpleTransform(Model $chat): array
    {
        return [
            'id' => $chat->id,
            'type' => $chat->type,
        ];
    }


    public function detailedTransform(Model $chat): array
    {
//        $from = ChatUserAction::where('user_id', Auth::id())->where('chat_id', $chat->id)->first();
//        dump($from);
//        $to = Message::where('chat_id', $chat->id)->latest()->first();
////          dd($to);

//        dd($chat->pivot);
        return [
            'chat_id' => $chat->id,
            'messeages_count' => $chat->messages_count,

//            'unread_messages' => Message::where('chat_id', $chat->id)->whereBetween('created_at', [$from->action_date, $to->created_at])->count(),
        ];
    }


}
