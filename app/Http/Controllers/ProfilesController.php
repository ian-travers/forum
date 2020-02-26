<?php

namespace App\Http\Controllers;

use App\User;

class ProfilesController extends Controller
{
    public function show(User $user)
    {
        $activities = $user->activity()->latest()->with('subject')->take(50)->get()->groupBy(function ($activity) {
            return $activity->created_at->format('Y-m-d');
        });

        return view('profiles.show', [
            'userProfile' => $user,
            'activities' => $activities,
        ]);
    }
}
