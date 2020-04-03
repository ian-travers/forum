<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class UserAvatarController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store()
    {
        request()->validate([
            'avatar' => 'required|image',
        ]);

        auth()->user()->update([
            'avatar_path' => request()->file('avatar')->store('avatars', 'public')
        ]);

        return response([], Response::HTTP_NO_CONTENT);
    }
}
