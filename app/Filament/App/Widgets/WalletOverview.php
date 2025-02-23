<?php

namespace App\Filament\App\Widgets;

use App\Models\AccountType;
use App\Models\Notification;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Support\Enums\Alignment;
use Filament\Widgets\Widget;

class WalletOverview extends Widget implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    protected static string $view = 'filament.app.widgets.wallet-overview';


    protected function getViewData(): array
    {
        return [
            'user' => auth()->user(),
            'notifications' => Notification::query()->where('is_active', true)->get()
        ];
    }

    public function upgradeAccount()
    {
        $plans = AccountType::query()->where('id', '>', auth()->user()->account_type_id)->where('name', '!=', 'staff')->first();
        return Action::make('upgradeAccount')
            ->label('Upgrade Account')
            ->modalHeading('Upgrade Account')
            ->modalSubheading('Wallet Balance: ' . auth()->user()->wallet_balance)
            ->modalWidth('max-w-xl')
            ->modalContent(view('filament.app.pages.actions.upgrade', ['plans' => $plans]))
            ->modalSubmitActionLabel('Upgrade')
            ->disabled(function () {
                $user = auth()->user();
                if ($user->accountType->name == 'staff' || $user->accountType->name == 'gold')
                    return true;
                if ($user->accountType->name == 'silver' && $user->wallet_balance < 3000)
                    return true;
                if ($user->accountType->name == 'bronze' && $user->wallet_balance < 2000)
                    return true;
                return false;
            })
            ->action(function (array $data) use ($plans) {
                $user = auth()->user();
                if ($user->accountType->name == 'bronze') {
                    $user->withdraw(2000);
                    $user->update(['account_type_id' => $plans->id]);
                } else if ($user->accountType->name == 'silver') {
                    $user->withdraw(3000);
                    $user->update(['account_type_id' => $plans->id]);
                }
                FilamentNotification::make('Account Upgraded')
                    ->body('Your account has been upgraded to ' . $plans->name)
                    ->success()
                    ->send();



            })
            ->modalIcon('heroicon-o-arrow-trending-up')
            ->modalAlignment(Alignment::Center)
            ->closeModalByClickingAway(false);
    }
}