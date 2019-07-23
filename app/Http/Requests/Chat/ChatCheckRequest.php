<?php


namespace App\Http\Requests\Chat;

use App\Chat;
use App\Http\Requests\DataPersistRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;


class ChatCheckRequest extends DataPersistRequest
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

        if ($chat = Auth::user()->chats()->whereHas('users', function (Builder $query) {
            $query->where('users.id', $this->user->id);
        })->first()) {
            $this->chat = $chat;
        }else{
        $newChat = Chat::create();
        $newChat->users()->attach([Auth::user()->id, $this->user->id]);
        $this->chat = $newChat;
        }

        return $this;
    }

}
