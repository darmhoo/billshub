<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Widgets\AccountFundingList;
use App\Filament\App\Widgets\StatsOverview;
use App\Filament\App\Widgets\TransactionsWidget;
use App\Filament\App\Widgets\WalletOverview;
use App\Models\OnboardingMessage;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Support\Enums\Alignment;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Str;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public $defaultAction = 'onboarding';



    protected static string $view = 'filament.app.pages.dashboard';

    public function getTitle(): string|HtmlString
    {
        return Str::of('<div class="flex items-center"><span class="text-sm mx-2 inline-block ><svg xmlns="http://www.w3.org/2000/svg" fill="" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
</svg> 
</span>' . '<span class="text-sm font-normal mx-2 inline-block">Welcome back, ' . auth()->user()->name . '</span></div>')->toHtmlString();
    }

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
        return [WalletOverview::class, AccountFundingList::class, TransactionsWidget::class];
    }

    public function getHeaderWidgetsColumns(): array|int|string
    {
        return 1;
    }

    public function onboardingAction()
    {
        $notification = OnboardingMessage::where('is_active', true)->first();

        return Action::make('onboarding')
            ->modalHeading($notification->title ?? '')
            ->modalDescription($notification->content ?? '')
            ->disabledForm()
            ->modalSubmitAction(false)
            ->modalCancelAction(false)
            ->modalAlignment(Alignment::Center)
            ->visible($notification !== null);


    }

    public function upgradeAccountAction()
    {
        // dd('i am here');
        return Action::make('upgradeAccount')
            ->form([
                Select::make('plan')
                    ->options([
                        'basic' => 'Basic',
                        'premium' => 'Premium',
                        'enterprise' => 'Enterprise',
                    ])
                    ->required()
                    ->default('basic'),
            ]);
    }



}
