<?php

namespace App\Filament\App\Pages;

use App\Models\AirtimeBundle;
use App\Models\Automation;
use App\Models\Network;
use App\Models\User;
use App\Services\AirtimeService\AutoPilot;
use App\Services\AirtimeService\VTPass;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Filament\Notifications\Livewire\Notifications;
use Request;

class BuyAirtime extends Page implements HasForms
{
    protected static ?string $navigationIcon = 'heroicon-o-phone-arrow-down-left';

    protected static string $view = 'filament.app.pages.buy-airtime';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Services';

    public ?string $network = null;
    public ?string $phoneNumber = null;
    public ?string $amountToPurchase = null;

    public ?string $amountToPay = null;


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('network')
                    // ->options(Network::query()->pluck('name', 'id'))
                    ->options(function (): array {
                        return Network::all()->pluck('name', 'id')->all();
                    })
                    ->required()
                    ->translateLabel()

                    ->placeholder('Choose network')
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                        $discount = AirtimeBundle::query()
                            ->where('network_id', $state)
                            ->where('account_type_id', auth()->user()->account_type_id)
                            ->where('is_active', 'active')
                            ->first();
                        // dd($discount);
                        if ($discount) {
                            $set('amountToPay', $get('amountToPurchase') - ($discount->discount * $get('amountToPurchase') / 100));
                        } else {
                            $set('amountToPay', $get('amountToPurchase'));

                        }
                    }),
                TextInput::make('amountToPurchase')
                    ->numeric()
                    ->prefix('₦')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                        $this->validateOnly('amountToPurchase');
                        $discount = AirtimeBundle::query()
                            ->where('network_id', $get('network'))
                            ->where('account_type_id', auth()->user()->account_type_id)
                            ->first();
                        // dd($discount);
                        if ($discount) {
                            $set('amountToPay', $state - ($discount->discount * $state / 100));
                        } else {
                            $set('amountToPay', $state);

                        }
                    })
                    ->disabled(function (Set $set, $state, Get $get) {
                        return $get('network') == null;
                    })
                ,

                TextInput::make('amountToPay')
                    ->required()
                    ->prefix('₦')
                    ->disabled()
                    ->numeric(),

                TextInput::make('phoneNumber')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function () {
                        $this->validateOnly('phoneNumber');

                    })
                    ->autocomplete()
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
                        ->modalHeading('Buy Airtime')
                        ->modalDescription(function (Get $get) {
                            return 'You are about to send ₦' . $get('amountToPurchase') . ' to ' . $get('phoneNumber');
                        })
                        ->modalSubmitActionLabel('Proceed')
                        ->form([
                            TextInput::make('transaction_pin')
                                ->required()
                                ->label('PIN')
                                ->numeric()
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
            ->columns(2)


        ;
    }
    public function save()
    {
        // dd('here');

        $discount = AirtimeBundle::query()
            ->where('network_id', $this->network)
            ->where('account_type_id', auth()->user()->account_type_id)
            ->first();
        $real = $discount ? $this->amountToPurchase - ($discount->discount * $this->amountToPurchase / 100) : $this->amountToPurchase;
        if (auth()->user()->balance < $real) {
            Notification::make()
                ->title('Insufficient balance')
                ->danger()
                ->send();
            return;
        }

        $automation = $discount->automation;
        // dd($automation);
        if ($automation->name === 'autopilot') {
            $autopilot = new AutoPilot($automation);
            $res = $autopilot->sendAirtime($this->phoneNumber, $this->amountToPurchase, $this->network, );
            if ($res['status'] === true) {
                auth()->user()->withdraw($real);
                $transaction = auth()->user()->transaction()->create([
                    'price' => $real,
                    'transaction_type' => 'airtime',
                    'status' => 'completed',
                    'reference' => $res['data']['reference'],
                    'amount_before' => auth()->user()->balance + $real,
                    'amount_after' => auth()->user()->balance,
                    'api_message' => $res['data']['message'],
                    'network' => Network::where('id', $this->network)->first()->name,
                    'phone_number' => $this->phoneNumber,
                ]);
                $this->phoneNumber = null;
                $this->amountToPurchase = null;
                $this->amountToPay = null;
                $this->network = null;
                Notification::make()
                    ->title('Airtime purchased successfully')
                    ->success()
                    ->send();
            } else {
                $transaction = auth()->user()->transaction()->create([
                    'price' => $real,
                    'transaction_type' => 'airtime',
                    'status' => 'failed',
                    'reference' => $res['data']['reference'] ?? '',
                    'amount_before' => auth()->user()->balance,
                    'amount_after' => auth()->user()->balance,
                    'api_message' => $res['data']['message'],
                    'network' => Network::where('id', $this->network)->first()->name,
                    'phone_number' => $this->phoneNumber,
                ]);
                Notification::make()
                    ->title('Airtime purchase failed')
                    ->danger()
                    ->send();
            }
        }
        if ($automation->name === 'VTPASS') {
            $vtpass = new VTPass($automation);
            $res = $vtpass->sendAirtime($this->phoneNumber, $this->amountToPurchase, $this->network, );
            // dd($res);
            if ($res['response_description'] === 'TRANSACTION SUCCESSFUL') {
                auth()->user()->withdraw($real);
                $transaction = auth()->user()->transaction()->create([
                    'price' => $real,
                    'transaction_type' => 'airtime',
                    'status' => 'completed',
                    'reference' => $res['requestId'],
                    'amount_before' => auth()->user()->balance + $real,
                    'amount_after' => auth()->user()->balance,
                    'api_message' => $res['response_description'],
                    'network' => Network::where('id', $this->network)->first()->name,
                    'phone_number' => $this->phoneNumber,
                ]);
                Notification::make()
                    ->title('Airtime purchased successfully')
                    ->success()
                    ->send();
            } else {
                $transaction = auth()->user()->transaction()->create([
                    'price' => $real,
                    'transaction_type' => 'airtime',
                    'status' => 'failed',
                    'reference' => $res['requestId'] ?? '',
                    'amount_before' => auth()->user()->balance,
                    'amount_after' => auth()->user()->balance,
                    'api_message' => $res['response_description'],
                    'network' => Network::where('id', $this->network)->first()->name,
                    'phone_number' => $this->phoneNumber,
                ]);
                Notification::make()
                    ->title('Airtime purchase failed')
                    ->danger()
                    ->send();
            }
        }

        //Todo wallet check

        //Todo use airtime api 

        //Todo Log transaction

    }







}
