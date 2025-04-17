<?php

namespace App\Filament\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Facades\Filament;

use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseLogin;

class LoginUser extends BaseLogin
{
    protected static string $view = 'filament-panels::pages.auth.login';


    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();

        if (!Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();
        if ($user->is_active == 0) {
            Notification::make()
                ->title('Your account is inactive')
                ->danger()
                ->send();

            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }
        if (
            ($user instanceof FilamentUser) &&
            (!$user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }









}
