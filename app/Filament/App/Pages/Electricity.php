<?php

namespace App\Filament\App\Pages;

use App\Models\Automation;
use App\Models\ElectricityProvider;
use App\Traits\TransactionTrait;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class Electricity extends Page
{
    use TransactionTrait;
    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    protected static string $view = 'filament.app.pages.electricity';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = 'Services';

    public ?string $electricityProvider = null;
    public ?string $type = null;

    public ?string $meter_number = null;

    public ?string $amountToPurchase = null;

    public ?string $phoneNumber = null;

    public $verifiedAccount = null;
    public ?bool $isVerified = false;
    public ?bool $insufficient = null;







    public function form(Form $form): Form
    {
        $automation = Automation::where('name', 'VTPASS')->first();
        $vtpass = new \App\Services\AirtimeService\VTPass($automation);
        return $form
            ->schema([
                Select::make('electricityProvider')
                    ->label('Electricity Provider')
                    ->options(\App\Models\ElectricityProvider::all()->pluck('name', 'id'))
                    ->live()
                    ->required(),
                Select::make('type')
                    ->label('Type')
                    ->options([
                        'prepaid' => 'Prepaid',
                        'postpaid' => 'Postpaid',
                    ])
                    ->live()
                    ->required(),
                TextInput::make('meter_number')
                    ->label('Meter Number')
                    ->required()
                    ->live()
                    ->suffix('Verify')
                    ->suffixAction(
                        Action::make('validate_meter_number')
                            ->disabled(function (Get $get) {
                                return $get('meter_number') === null || $get('electricityProvider') === null || $get('type') === null;
                            })
                            ->icon('heroicon-o-check-circle')
                            ->action(function (Get $get) use ($vtpass) {
                                // dd($get('meter_number'));
                                $response = $vtpass->verifyMeterNumber($get('meter_number'), $get('electricityProvider'), $get('type'));
                                // dd($response);
                                if (array_key_exists('error', $response['content'])) {
                                    // dd($response);
                                    $this->isVerified = false;
                                    Notification::make()
                                        ->warning()
                                        ->title('An Error occured')
                                        ->body($response['content']['error'])
                                        ->send();
                                } else {
                                    $this->verifiedAccount = $response['content'];
                                    $this->isVerified = true;
                                    Notification::make()
                                        ->title('Meter Number Verified')
                                        ->body('Meter number is valid')
                                        ->send();
                                }
                            })
                    ),

                TextInput::make('amountToPurchase')
                    ->label('Amount')
                    ->required()
                    ->live()
                    ->prefix('NGN')
                    ->afterStateUpdated(function (Set $set, $state) {
                        $set('insufficient', !$this->checkBalance(Auth::user(), $state));                        
                    })
                    ->disabled(function (Get $get) {
                        return $this->verifiedAccount === null;
                    }),

                TextInput::make('phoneNumber')
                    ->required()
                    ->tel()
                    ->telRegex('/0([7,8,9])([0,1])\d{8}$|234([7,8,9])([0,1])\d{8}$/')
                    ->placeholder('08026201234')
                    ->length(11)
                    ->live()
                    ->disabled(function (Get $get) {
                        return $this->verifiedAccount === null;
                    }),

                Actions::make([
                    Action::make('submit')
                        ->extraAttributes([
                            'class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full md:w-1/2',
                        ])
                        ->disabled(function (Get $get) {
                            return $this->checkBalance(Auth::user(), $get('amountToPurchase')) !== true  || $get('amountToPurchase') === null || $get('phoneNumber') === null || $this->verifiedAccount === null;
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Buy Airtime')
                        ->modalDescription(function (Get $get) {
                            return 'You are about to pay ₦' . $get('amountToPurchase') . ' for Electricity Bill';
                        })
                        ->modalSubmitActionLabel('Proceed')
                        ->form([
                            TextInput::make('transaction_pin')
                                ->required()
                                ->label('PIN')
                                ->password()
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
                ])

            ])


        ;
    }

    public function save()
    {
        $data = $this->form->getState();

        $automation = Automation::where('name', 'VTPASS')->first();
        // dd($automation);
        if ($this->checkBalance(Auth::user(), $data['amountToPurchase']) !== true) {
            Notification::make()
                ->warning()
                ->title('Insufficient balance')
                ->body('You do not have enough balance to complete this transaction!!!')
                ->send();

            return;
        }
        $vtpass = new \App\Services\AirtimeService\VTPass($automation);
        $response = $vtpass->payElectricity(meter_number: $this->meter_number, amount: $this->amountToPurchase, serviceId: $this->electricityProvider, type: $this->type, phone: $this->phoneNumber);
        // dd($response);
        if ($response['code'] == '000') {
            auth()->user()->withdraw($this->amountToPurchase);
            $transaction = auth()->user()->transaction()->create([
                'price' => $this->amountToPurchase,
                'transaction_type' => 'electricity',
                'status' => 'completed',
                'description' => 'Electricity Bill Payment ' . ElectricityProvider::where('id', $this->electricityProvider)->first()->name,
                'reference' => $response['requestId'],
                'amount_before' => auth()->user()->balance + $this->amountToPurchase,
                'amount_after' => auth()->user()->balance,
                'api_message' => $response['response_description'],
                'phone_number' => $this->phoneNumber,
            ]);
            return Notification::make()
                ->success()
                ->title('Electricity Bill Paid')
                ->body('Transaction done successfully')
                ->send();
        } else {
            $transaction = auth()->user()->transaction()->create([
                'price' => $this->amountToPurchase,
                'transaction_type' => 'electricity',
                'status' => 'failed',
                'reference' => $response['requestId'] ?? null,
                'description' => 'Electricity Bill Payment ' . ElectricityProvider::where('id', $this->electricityProvider)->first()->name,

                'amount_before' => auth()->user()->balance,
                'amount_after' => auth()->user()->balance,
                'api_message' => $response['response_description'] ?? 'An error occured',
                'phone_number' => $this->phoneNumber,
            ]);

            return Notification::make()
                ->title('An Error occured')
                ->danger()
                ->body('Transaction failed')
                ->send();

        }


    }



}
