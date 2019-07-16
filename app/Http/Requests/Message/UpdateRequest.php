<?php


namespace App\Http\Requests\Message;

use App\Http\Requests\DataPersistRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRequest extends DataPersistRequest
{

    public function authorize(): bool
    {
        return Auth::user()->can('update',$this->route('message'));
    }

    public function rules(): array
    {
        return [
            'message' => 'required|max:250',
        ];
    }

    public function persist(): self
    {
        $this->route('message')->update($this->getProcessedData());

        return $this;
    }

}