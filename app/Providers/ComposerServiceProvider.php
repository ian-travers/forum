<?php

namespace App\Providers;

use App\Channel;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class ComposerServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        view()->composer(['layouts.app', 'threads.create'], function (View $view) {
            $channels = Channel::all();

            return $view->with(compact('channels'));
        });
    }
}
