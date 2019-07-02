<?php


namespace App\Http\Requests\Post;

use App\Http\Requests\DataPersistRequest;
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

    protected function getMergingData(): array
    {
        return [
            'user_id' => Auth::user()->id,
        ];
    }

    public function persist(): self
    {
        $this->post->views()->create($this->getProcessedData());

        return $this;
    }


}