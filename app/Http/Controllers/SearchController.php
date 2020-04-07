<?php

namespace App\Http\Controllers;

use App\Thread;

class SearchController extends Controller
{
    public function show()
    {
        $search = request('q');

        return Thread::search($search)->paginate(20);
    }
}
