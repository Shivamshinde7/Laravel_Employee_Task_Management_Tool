<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Channel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
View::composer('layouts.sidebar', function ($view) {
        if (Auth::check()) {
            $users = User::where('id', '!=', Auth::id())->get();
            $channels = Channel::with('members')->get();
            $defaultChannel = Channel::with('messages.sender')->first();

            $view->with([
                'users' => $users,
                'channels' => $channels,
                'defaultChannel' => $defaultChannel
            ]);
        }
    });

    }

    
}
