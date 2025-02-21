<?php

namespace App\Filament\App\Pages;

use App\Models\DataBundle;
use App\Models\DataType;
use App\Models\Network;
use App\Services\AirtimeService\AutoPilot;
use App\Services\AirtimeService\VTPass;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Request;

class BuyData extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-wifi';

    protected static string $view = 'filament.app.pages.buy-data';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Services';

    public ?string $network = null;
    public ?string $bundle = null;
    public ?string $price = null;
    public ?string $data_type = null;

    public ?string $phoneNumber = null;


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('network')
                    ->options(function (): array {
                        return Network::all()->pluck('name', 'id')->all();
                    })
                    ->required()

                    ->placeholder('Choose network')
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                        $plan = DataBundle::query()
                            ->where('network_id', $state)
                            ->where('is_active', true)
                            ->where('account_type_id', auth()->user()->account_type_id)
                            ->first();
                        // dd($discount);
                        if ($plan) {
                            $set('price', $plan->price);
                        } else {
                            $set('price', 0.00);

                        }
                    }),

                Select::make('data_type')
                    ->required()
                    ->options(fn(Get $get): Collection => DataType::query()
                        ->pluck('name', 'id'))
                    ->suffixIcon('heroicon-m-globe-alt')
                    ->suffixIconColor('success')
                    ->disabled(function (Get $get): bool {
                        return $get('network') === null;
                    })
                    ->live()
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $set('price', DataBundle::query()->where('id', '=', $state)->first()->price ?? 0.00);
                    })
                    ->placeholder('Choose Bundle'),

                Select::make('bundle')
                    ->required()
                    ->options(fn(Get $get): Collection => DataBundle::query()
                        ->where('network_id', '=', $get('network'))
                        ->where('data_type_id', '=', $get('data_type'))
                        ->where('is_active', true)

                        ->where('account_type_id', auth()->user()->account_type_id)

                        ->pluck('name', 'id'))
                    ->disabled(function (Get $get): bool {
                        return $get('data_type') === null;
                    })
                    ->suffixIcon('heroicon-m-globe-alt')
                    ->suffixIconColor('success')
                    ->live()

                    ->disabled(function (Get $get): bool {
                        return $get('network') === null;
                    })
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $set('price', DataBundle::query()->where('id', '=', $state)->first()->price ?? 0.00);
                    })
                    ->placeholder('Choose Bundle'),

                TextInput::make('price')
                    ->required()
                    ->prefix('₦')
                    ->disabled()
                    ->numeric(),

                TextInput::make('phoneNumber')
                    ->required()
                    ->regex('(^0)(7|8|9){1}(0|1){1}[0–9]{8})')
                    ->placeholder('08026201234')
                    ->length(11),


                Actions::make([
                    Action::make('submit')
                        ->extraAttributes([
                            'class' => 'bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full md:w-1/2',
                        ])
                        ->requiresConfirmation()
                        ->modalHeading('Buy Data')
                        ->modalDescription(function (Get $get) {
                            return 'You are about to send ' . DataBundle::where('id', $get('bundle'))->first()->name . ' to ' . $get('phoneNumber');
                        })
                        ->modalSubmitActionLabel('Proceed')
                        ->form([
                            TextInput::make('transaction_pin')
                                ->required()
                                ->password()
                                ->label('PIN')
                                ->maxLength(8)
                                ->minLength(4)
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

            ->columns(1)
        ;
    }

    public function save()
    {
        // dd('here');


        if (auth()->user()->balance < $this->price) {
            Notification::make()
                ->title('Insufficient balance')
                ->danger()
                ->send();
            return;
        }
        $bundle = DataBundle::where('id', $this->bundle)->first();

        $automation = $bundle->automation;
        if ($automation->name === 'autopilot') {
            $autopilot = new AutoPilot($automation);
            $res = $autopilot->buyData($this->phoneNumber, DataBundle::where('id', $this->bundle)->first()->plan_id, $this->network, DataType::where('id', $this->data_type)->first()->name);
            if ($res['status'] === true) {
                auth()->user()->withdraw($this->price);
                $transaction = auth()->user()->transaction()->create([
                    'price' => $this->price,
                    'transaction_type' => 'data',
                    'status' => 'completed',
                    'reference' => $res['data']['reference'],
                    'amount_before' => auth()->user()->balance + $this->price,
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
                    ->title('Data purchased successfully')
                    ->success()
                    ->send();
            } else {
                $transaction = auth()->user()->transaction()->create([
                    'price' => $this->price,
                    'transaction_type' => 'data',
                    'status' => 'failed',
                    'reference' => $res['data']['reference'] ?? '',
                    'amount_before' => auth()->user()->balance,
                    'amount_after' => auth()->user()->balance,
                    'api_message' => $res['data']['message'],
                    'network' => Network::where('id', $this->network)->first()->name,
                    'phone_number' => $this->phoneNumber,
                ]);
                Notification::make()
                    ->title('Data purchase failed')
                    ->danger()
                    ->send();
            }
        }
        if ($automation->name === 'VTPASS') {
            $vtpass = new VTPass($automation);
            $res = $vtpass->sendAirtime($this->phoneNumber, $this->amountToPurchase, $this->network, );
            // dd($res);
            if ($res['response_description'] === 'TRANSACTION SUCCESSFUL') {
                auth()->user()->withdraw($this->price);
                $transaction = auth()->user()->transaction()->create([
                    'price' => $this->price,
                    'transaction_type' => 'airtime',
                    'status' => 'completed',
                    'reference' => $res['requestId'],
                    'amount_before' => auth()->user()->balance + $this->price,
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
                    'price' => $this->price,
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

    protected function onValidationError(\Illuminate\Validation\ValidationException $exception): void
    {
        Notification::make()
            ->title($exception->getMessage())
            ->danger()
            ->send();
    }
}
