<?php

namespace App\Filament\App\Pages;

use App\Models\AirtimeBundle;
use App\Models\Network;
use App\Services\AirtimeService\AutoPilot;
use App\Services\AirtimeService\VTPass;
use App\Traits\TransactionTrait;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Boolean;

class BuyAirtime extends Page implements HasForms
{
    use InteractsWithForms, TransactionTrait;
    protected static ?string $navigationIcon = 'heroicon-o-phone-arrow-down-left';

    protected static string $view = 'filament.app.pages.buy-airtime';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Services';

    public ?string $network = null;
    public ?string $phoneNumber = null;
    public ?string $amountToPurchase = null;

    public ?string $amountToPay = null;

    public ?bool $insufficient = null;


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
                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                        $set('amountToPay', null);
                        $set('amountToPurchase', null);
                    }),

                TextInput::make('amountToPurchase')
                    ->numeric()
                    ->prefix('₦')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                        // $this->validateOnly('amountToPurchase');
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
                        $set('insufficient', !$this->checkBalance(Auth::user(), $state));
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
                    ->tel()
                    ->telRegex('/0([7,8,9])([0,1])\d{8}$|234([7,8,9])([0,1])\d{8}$/')
                    ->placeholder('08026201234')
                    ->length(11)
                    ->live()
                    ->afterStateUpdated(function () {
                        // $this->validateOnly('phoneNumber');
            
                    })
                    ->autocomplete()
                ,

                Actions::make([
                    Action::make('submit')
                        ->extraAttributes([
                            'class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full md:w-1/2',
                        ])
                        ->disabled(function (Set $set, $state, Get $get) {
                            return $this->checkBalance(Auth::user(), $get('amountToPay')) !== true || $get('network') == null || $get('phoneNumber') == null || $get('amountToPurchase') == null;
                        })
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
                            $this->checkPin($data['transaction_pin']);
                        }),
                ])

            ])
            ->columns(2);
    }

    public function create()
    {
        // dd('here');
        $data = $this->form->getState();

        $discount = AirtimeBundle::query()
            ->where('network_id', $data['network'])
            ->where('account_type_id', auth()->user()->account_type_id)
            ->first();
        $real = $discount ? $this->amountToPurchase - ($discount->discount * $this->amountToPurchase / 100) : $this->amountToPurchase;
        if ($this->checkBalance(Auth::user(), $real) !== true) {
            Notification::make()
                ->warning()
                ->title('Insufficient balance')
                ->body('You do not have enough balance to complete this transaction!!!')
                ->send();

            return;
        }
        // dd($real);

        $automation = $discount->automation;
        // dd($automation);
        if ($automation->name === 'autopilot') {
            $autopilot = new AutoPilot($automation);
            $res = $autopilot->sendAirtime($this->phoneNumber, $this->amountToPurchase, $this->network, );
            if ($res['status'] === true) {
                $this->deductBalance(Auth::user(), $real);
                //TODO clean up this code
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
                $this->form->fill();
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
                $this->deductBalance(Auth::user(), $real);
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
                $this->form->fill();

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
    private function checkPin($pin)
    {
        // $this->form->getState();
        $user = Auth::user();
        if ($user->transaction_pin === null) {
            return Notification::make()
                ->warning()
                ->title('Invalid Pin')
                ->body('You need to set your transaction pin before you can proceed!!!')
                ->send();
        } else if ($user->transaction_pin !== $pin) {
            return Notification::make()
                ->warning()
                ->title('Invalid Pin')
                ->body('Pin is incorrect!!!')
                ->send();
        } else {
            $this->create();
        }
    }









}
