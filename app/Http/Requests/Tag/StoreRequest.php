<?php


namespace App\Http\Requests\Tag;

use App\Http\Requests\DataPersistRequest;
use App\Tag;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends DataPersistRequest
{

    public function authorize(): bool
    {
        return Auth::user()->can('create', Tag::class);
    }

    public function rules(): array
    {
        return [
            'tag_word' => 'required|max:20',
        ];
    }

    public function persist(): self
    {
        Tag::create($this->getProcessedData());

        return $this;
    }

}