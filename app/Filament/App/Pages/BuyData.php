<?php

namespace App\Filament\App\Pages;

use App\Models\DataBundle;
use App\Models\Network;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
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
                            // ->where('account_type_id', auth()->user()->account_type_id)
                            ->first();
                        // dd($discount);
                        if ($plan) {
                            $set('price', $plan->price);
                        } else {
                            $set('price', 0.00);

                        }
                    }),
                Select::make('bundle')
                    ->required()
                    ->options(fn(Get $get): Collection => DataBundle::query()
                        ->where('network_id', '=', $get('network'))
                        // ->where('account_type_id', '=', auth()->user()->account_type_id)
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
                TextInput::make('price')
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
