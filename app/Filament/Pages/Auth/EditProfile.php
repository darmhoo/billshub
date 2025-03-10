<?php

namespace App\Filament\Pages\Auth;

use Filament\Facades\Filament;
use Filament\Forms\Components\Actions\Action;
use Filament\Actions\Action as FilamentAction;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Filament\Support\Enums\Alignment;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Throwable;
use function Filament\Support\is_app_url;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([

                $this->getNameFormComponent()->disabled(),
                $this->getEmailFormComponent()->disabled(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                TextInput::make('phone_number')
                    ->numeric()
                    ->maxLength(11)->disabled(),
                TextInput::make('transaction_pin')
                    ->label('Transaction PIN')
                    ->password()
                    ->disabled()
                    ->suffix('Set PIN')
                    ->length(4)
                    ->suffixAction(
                        Action::make('resetPin')
                            ->label('Reset PIN')

                            ->icon('heroicon-o-arrow-path')
                            ->form([
                                TextInput::make('password')
                                    ->password()
                                    ->revealable()
                                    ->required()
                                    ->label('Password'),
                                TextInput::make('newPin')
                                    ->numeric()
                                    ->inputMode('numeric')
                                    ->required()
                                    ->placeholder('4 digit pin')
                                    ->label('New PIN')
                                    ->length(4)
                                    ->maxLength(4)
                            ])->action(fn($data) => $this->resetPin($data))

                    )
                ,
            ]);

    }

    public function resetPin($request)
    {

        $user = $this->getUser();

        if (!password_verify($request['password'], $user->password)) {
            $this->addError('password', 'Invalid password');
            Notification::make()
                ->title('Invalid password')
                ->danger()
                ->send();
            return;
        }

        $user->update([
            'transaction_pin' => $request['newPin'],
        ]);

        Notification::make()
            ->title('Pin reset successfully')
            ->success()
            ->send();
    }

    public function getFormActionsAlignment(): string|Alignment
    {
        return Alignment::End;
    }

    public function save(): void
    {
        try {
            $this->beginDatabaseTransaction();

            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            $this->handleRecordUpdate($this->getUser(), $data);

            $this->callHook('afterSave');

            $this->commitDatabaseTransaction();
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction() ?
                $this->rollBackDatabaseTransaction() :
                $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        if (request()->hasSession() && array_key_exists('password', $data)) {
            request()->session()->put([
                'password_hash_' . Filament::getAuthGuard() => $data['password'],
            ]);
        }

        $this->data['password'] = null;
        $this->data['passwordConfirmation'] = null;

        $this->getSavedNotification()?->send();

        if ($redirectUrl = $this->getRedirectUrl()) {
            $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
        }

    }
}