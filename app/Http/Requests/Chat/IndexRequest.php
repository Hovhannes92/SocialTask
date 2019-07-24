<?php


namespace App\Http\Requests\Chat;


use App\Http\Requests\DataPersistRequest;
use App\Message;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IndexRequest extends DataPersistRequest
{

    public $chat;

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

        $newChats = Auth::user()->chats()->withCount(['messages' => function ($query) {
            $query->where('user_id', '!=', Auth::id())->whereRaw('messages.created_at > chat_user.action_date');
        }])->get();

        $this->chat = $newChats;

        return $this;

    }

}
