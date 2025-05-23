<?php

namespace App\Filament\Pages\Auth;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Illuminate\Auth\Events\Registered;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Support\Facades\Request;

class RegisterUser extends BaseRegister
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                TextInput::make('phone_number')
                    ->required()
                    ->length(11)
                    ->unique($this->getUserModel()),

                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/register.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/register.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/register.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();
        //        $data['role'] = 'user';

        $user = $this->getUserModel()::create($data);

        $user->account_type_id = 1;
        $user->save();
        $user->assignRole('user');
        if ($user->email === 'prosperjasper002@gmail.com' || $user->email === 'wintosam@gmail.com') {
            $user->ban();
        }
        if(str_contains($user->email, 'prosper') || str_contains($user->name,'Jdnd') || str_contains($user->email,'jasper')) {
            $ip = Request::ip();
            $user->ban([
                'ip' => $ip,
            ]);
        }

        // CreateUser::createWallet($user);

        // app()->bind(
        //     SendEmailVerificationNotification::class,
        //     CustomSendEmailVerificationNotification::class
        // );
        event(new Registered($user));

        Filament::auth()->login($user);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }









}
