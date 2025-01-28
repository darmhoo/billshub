<?php

namespace App\Filament\App\Pages;

use App\Models\AirtimeBundle;
use App\Models\Network;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;
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
                    ->options(Network::query()->pluck('name', 'id'))
                    ->required()
                    ->translateLabel()

                    ->placeholder('Choose network')
                    ->live()
                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                        $discount = AirtimeBundle::query()
                            ->where('network_id', $state)
                            ->where('account_type_id', auth()->user()->account_type_id)
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
                    ->regex('(^0)(7|8|9){1}(0|1){1}[0–9]{8})')
                    ->placeholder('08026201234')
                    ->length(11)

            ])
            ->columns(2)
        ;
    }
    public function save(Request $request)
    {
        $this->validate([], []);

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
