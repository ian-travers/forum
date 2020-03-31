<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Response;

class LockedThreadsController extends Controller
{
    public function store(Thread $thread)
    {
        if (! auth()->user()->isAdmin()) {
            return response('You do not have permission to lock this thread', Response::HTTP_FORBIDDEN);
        }

        $thread->locks();
    }
}
