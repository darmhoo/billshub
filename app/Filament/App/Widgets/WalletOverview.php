<?php

namespace App\Filament\App\Widgets;

use Filament\Widgets\Widget;

class WalletOverview extends Widget
{
    protected static string $view = 'filament.app.widgets.wallet-overview';

    protected function getViewData(): array
    {
        return [
            'user' => auth()->user(),
        ];
    }
}
