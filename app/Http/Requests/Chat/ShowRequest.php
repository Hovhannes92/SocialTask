<?php


namespace App\Http\Requests\Chat;


use App\Http\Requests\DataPersistRequest;
use App\Message;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;




class ShowRequest extends DataPersistRequest
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
        $this->route('chat')->users()->updateExistingPivot(Auth::id(),['action_date' => Carbon::now()]);

        return $this;
    }


}
