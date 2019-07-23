<?php


namespace App\Http\Requests\Chat;


use App\Http\Requests\DataPersistRequest;
use App\Message;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IndexRequest extends DataPersistRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function persist(): self
    {

//        $from = Auth::user()->chats()->where('chat_id', $this->route('chat')->id)->first()->pivot->action_date ;

        dd(Auth::user()->chats()->withCount(['messages' => function ($query) {
            $query->whereRaw('messages.created_at > chat_user.action_date');
        }])->get()->toArray());
        $to = Message::where('chat_id', $this->route('chat')->id)->latest()->first()->created_at;

        dd($to);


    }


}
