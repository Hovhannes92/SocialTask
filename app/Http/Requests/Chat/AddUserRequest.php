<?php


namespace App\Http\Requests\Chat;

use App\Chat;
use App\Http\Requests\DataPersistRequest;


class AddUserRequest extends DataPersistRequest
{
    public $chat;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user' => 'required|exists:users,id',
        ];
    }

    protected function getMergingData(): array
    {
        return [
            'type' => Chat::TYPES['group'],
            ];
    }

    public function persist(): self
    {
        $chatMembers = $this->route('chat')->users()->pluck('id')->toArray();

        if($this->route('chat')->type == 1) {

            $newChat = Chat::create($this->getProcessedData());
            array_push($chatMembers, $this->user);
            $newChat->users()->attach($chatMembers);
            $this->chat = $newChat;

        }else{

            $this->route('chat')->users()->attach($this->user);
            $this->chat = $this->route('chat');
        }

        return $this;
    }

}
