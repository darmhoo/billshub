<?php

namespace App\Listeners;

use Filament\Facades\Filament;
use Filament\Notifications\Auth\VerifyEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CustomSendEmailVerificationNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        //
        $user = $event->user;
        $notification = new VerifyEmail;
        $notification->url = Filament::getVerifyEmailUrl($user);
        $user->notify($notification);
    }
}