<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        $activities =  $user->activity()->latest()->with('subject')->paginate(20);

        return view('profiles.show', [
            'userProfile' => $user,
            'activities' => $activities,
        ]);
    }
}
