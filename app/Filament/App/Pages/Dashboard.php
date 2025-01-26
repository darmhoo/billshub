<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Widgets\AccountFundingList;
use App\Filament\App\Widgets\StatsOverview;
use App\Filament\App\Widgets\TransactionsWidget;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Enums\Alignment;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public $defaultAction = 'onboarding';



    protected static string $view = 'filament.app.pages.dashboard';

    protected static ?string $title = "Dashboard";
    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = 1;


    // public function onboardingAction(): Action
    // {
    //     return Action::make('onboarding')
    //         ->modalHeading('Gbills247')
    //         ->modalDescription('You are welcome to GBILLZ247 where there is endless possibilities')
    //         ->disabledForm()
    //         ->modalSubmitAction(false)
    //         ->modalCancelAction(false)
    //         ->modalAlignment(Alignment::Center)
    //         ->visible(true);
    // }

    protected function getHeaderWidgets(): array
    {
        return [StatsOverview::class, AccountFundingList::class, TransactionsWidget::class];
    }

    public function getHeaderWidgetsColumns(): array|int|string
    {
        return 1;
    }

}
