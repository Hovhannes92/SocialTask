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
            'tag' => 'array',
            'tag.*.id' => 'id|exists:tags',
        ];
    }

    public function persist(): self
    {
        $this->post = Auth::user()->posts()->create($this->getProcessedData());

        $this->post->tags()->sync($this->tag);

        return $this;
    }

}