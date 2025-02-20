<?php

namespace App\Filament\App\Pages;

use App\Models\Automation;
use App\Models\Network;
use App\Services\AirtimeService\AutoPilot;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Actions\Action as FilamentAction;
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
                    ->live()
                    ->afterStateUpdated(function () {
                        $this->validateOnly('network');
                    })
                    ->required()
                    ->translateLabel()

                    ->placeholder('Choose network')
                ,


                TextInput::make('airtimeAmount')
                    ->live()
                    ->required()
                    ->prefix('₦')
                    ->numeric()
                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                        $this->validateOnly('airtimeAmount');

                        $discount = 10;
                        // dd($discount);
                        if ($discount) {
                            $set('paidCash', $state - ($discount * $state / 100));
                        } else {
                            $set('amountToPay', $get('amountToPurchase'));

                        }
                    })
                ,

                TextInput::make('paidCash')
                    ->required()
                    ->disabled()
                    ->prefix('₦')
                    ->numeric(),

                TextInput::make('phoneNumber')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function () {
                        $this->validateOnly('phoneNumber');

                    })
                    ->tel()
                    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                    ->placeholder('08026201234')
                    ->length(11),

                Actions::make([

                    Action::make('submit')
                        ->extraAttributes([
                            'class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full md:w-1/2',
                        ])
                        ->requiresConfirmation()
                        ->modalHeading('Sell airtime')
                        ->modalDescription(function (Get $get) {
                            return 'You are about to send ₦' . $get('amountToPurchase') . ' to ' . $get('phoneNumber');
                        })

                        ->modalSubmitActionLabel('Proceed')
                        ->form([
                            TextInput::make('OTP')
                                ->required()
                                ->label('OTP')
                        ])
                        ->action(function (array $data) {
                            if (auth()->user()->transaction_pin === null) {
                                return Notification::make()
                                    ->warning()
                                    ->title('Invalid Pin')
                                    ->body('You need to set your transaction pin before you can proceed!!!')
                                    ->send();
                            } else if (auth()->user()->transaction_pin !== $data['transaction_pin']) {
                                return Notification::make()
                                    ->warning()
                                    ->title('Invalid Pin')
                                    ->body('Pin is incorrect!!!')
                                    ->send();
                            } else {
                                $this->save();
                            }

                        })
                        ->modal()
                ])

            ])
            ->columns(2)

        ;
    }

    public function save()
    {
        $this->showOtp();

        $this->validate();
        $automation = Automation::where('name', 'autopilot')->first();
        $autoPilot = new AutoPilot($automation);

        $res = $autoPilot->sendOtp($this->network, $this->phoneNumber);
        if ($res) {
        } else {
            Notification::make()
                ->title('Something went wrong. Please try again later')
                ->danger()
                ->send();
        }

    }

    public function showOtp()
    {
        $automation = Automation::where('name', 'autopilot')->first();
        $autoPilot = new AutoPilot($automation);

        $res = $autoPilot->sendOtp($this->network, $this->phoneNumber);
        if ($res) {
            return FilamentAction::make('showOtp')
                ->form([
                    TextInput::make('otp')
                        ->label('OTP')
                        ->required()
                ])
                ->label('Submit')
                ->modalHeading('Enter OTP')
                ->modalWidth('max-w-xl')
                ->modal();
        } else {
            Notification::make()
                ->title('Something went wrong. Please try again later')
                ->danger()
                ->send();
        }


    }


}
