<?php


namespace App\Http\Requests\Message;


use App\Http\Requests\DataPersistRequest;
use Illuminate\Support\Facades\Auth;

class DestroyRequest extends DataPersistRequest
{

    public function authorize(): bool
    {
        return Auth::user()->can('delete',$this->route('message'));
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function persist(): self
    {
        $this->$this->route('message')->delete();

        return $this;
    }

    public function getMessage(): string
    {
        return "Comment successfully deleted.";
    }


}