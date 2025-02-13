<?php

namespace App\Filament\App\Pages;

use App\Models\Automation;
use App\Models\Network;
use App\Services\AirtimeService\AutoPilot;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Request;


class AirtimeCash extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static string $view = 'filament.app.pages.airtime-cash';

    protected static ?string $title = "Convert Airtime to Cash";
    protected static ?string $navigationLabel = 'Airtime 2 Cash';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Services';

    public ?string $network = null;
    public ?string $phoneNumber = null;
    public ?string $airtimeAmount = null;
    public ?string $paidCash = null;
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('network')
                    ->options(function (): array {
                        return Network::all()->pluck('name', 'id')->all();
                    })
                    ->required()
                    ->translateLabel()

                    ->placeholder('Choose network')
                    ->live()
                ,


                TextInput::make('airtimeAmount')
                    ->required()
                    ->prefix('₦')
                    ->numeric()
                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                        $discount = 10;
                        // dd($discount);
                        if ($discount) {
                            $set('paidCash', $state - ($discount * $state / 100));
                        } else {
                            $set('amountToPay', $get('amountToPurchase'));

                        }
                    })
                    ->live(),

                TextInput::make('paidCash')
                    ->required()
                    ->disabled()
                    ->prefix('₦')
                    ->numeric(),

                TextInput::make('phoneNumber')
                    ->required()
                    ->regex('(^0)(7|8|9){1}(0|1){1}[0–9]{8})')
                    ->placeholder('08026201234')
                    ->length(11),

            ])
            ->columns(2)

        ;
    }

    public function save(Request $request)
    {
        $automation = Automation::where('name', 'autopilot')->first();
        $autoPilot = new AutoPilot($automation);

        $res = $autoPilot->sendOtp($this->network, $this->phoneNumber);
        if ($res) {
            $this->showOtp();
        } else {
            Notification::make()
                ->title('Something went wrong. Please try again later')
                ->danger()
                ->send();
        }

    }

    public function showOtp()
    {
        return Action::make('showOtp')
            ->form([
                TextInput::make('otp')
                    ->label('OTP')
                    ->required()
            ])
            ->label('Enter OTP')
            ->modalHeading('Enter OTP')
            ->modalWidth('max-w-xl')
            ->modal();
    }


}
