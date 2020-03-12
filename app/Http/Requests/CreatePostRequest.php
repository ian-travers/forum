<?php

namespace App\Http\Requests;

use App\Exceptions\ThrottleException;
use App\Reply;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreatePostRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('create', new Reply());
    }

    public function rules()
    {
        return [
            'body' => 'required|spamfree',
        ];
    }

    /**
     * @throws ThrottleException
     */
    protected function failedAuthorization()
    {
        throw new ThrottleException('You are replying too frequently. Please take a break.');
    }
}
