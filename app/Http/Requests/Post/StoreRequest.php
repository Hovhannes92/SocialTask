<?php


namespace App\Http\Requests\Post;

use App\Http\Requests\DataPersistRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends DataPersistRequest
{
    public $post;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|max:20',
            'subtitle' => 'required|max:20',
            'description' => 'required|max:200',
        ];
    }

    public function persist(): self
    {
        $this->post = Auth::user()->posts()->create($this->getProcessedData());

        return $this;
    }



}