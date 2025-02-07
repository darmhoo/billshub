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
        $plans = AccountType::query()->where('id', '>', auth()->user()->account_type_id)->where('name', '!=', 'staff')->get();
        return Action::make('upgradeAccount')
            ->label('Upgrade Account')
            ->modalHeading('Upgrade Account')
            ->modalSubheading('Wallet Balance: ' . auth()->user()->wallet_balance)
            ->modalWidth('max-w-xl')
            ->form([
                Select::make('plan')
                    ->options(AccountType::query()->where('id', '>', auth()->user()->account_type_id)->where('name', '!=', 'staff')->pluck('name', 'id'))
                    ->required()
                    ->prefix(function ($state) {
                        if ($state == 2) {
                            return '₦1,000';
                        } else if ($state == 3) {
                            return '₦2,000';
                        } else
                            return '';
                    })->live()
            ])
            ->disabled(function () {
                return auth()->user()->wallet_balance > 1000;
            })
            ->action(function (array $data) {
                auth()->user()->wallet->withdraw(200);

            })
            ->modalIcon('heroicon-o-arrow-trending-up')
            ->modalAlignment(Alignment::Center)
            ->closeModalByClickingAway(false);
    }
}