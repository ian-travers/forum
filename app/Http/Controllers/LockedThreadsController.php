<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Response;

class LockedThreadsController extends Controller
{
    public function store(Thread $thread)
    {
        $thread->locks();
    }
}
