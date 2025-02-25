<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AirtimeTransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Infolists;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;



class AirtimeTransactionResource extends Resource
{
    // protected static ?string $model = AirtimeTransaction::class;
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationLabel = 'Airtime Transactions';



    protected static ?string $navigationGroup = 'Airtime';


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->filters([
                //
                SelectFilter::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ])
                    ->label('Status'),

                SelectFilter::make('network')
                    ->options([
                        'mtn' => 'MTN',
                        'airtel' => 'Airtel',
                        'glo' => 'Glo',
                        '9mobile' => '9mobile',
                    ])
                    ->label('Network'),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['created_from'] && !$data['created_until']) {
                            return null;
                        }

                        return 'Created at ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                    })


            ])
            ->query(Transaction::query()->where('transaction_type', 'airtime')->latest())
            ->columns([
                //
                TextColumn::make('user.name')->formatStateUsing(fn(string $state): string => ucfirst($state))->searchable(),

                TextColumn::make('transaction_type')->formatStateUsing(fn(string $state): string => ucfirst($state))->searchable()->label('Type'),
                TextColumn::make('reference')->searchable()->copyable()
                    ->copyMessage('reference copied')
                    ->copyMessageDuration(1500),

                TextColumn::make('phone_number')->searchable()->label('Phone')->copyable()
                    ->copyMessage('phone number copied')
                    ->copyMessageDuration(1500),

                TextColumn::make('network')->formatStateUsing(fn(string $state): string => strtoupper($state))->searchable(),
                TextColumn::make('price')->money('NGN')->label('Amount'),
                TextColumn::make('amount_before')->money('NGN')->label('Bal Before'),
                TextColumn::make('amount_after')->money('NGN')->label('Bal After'),
                TextColumn::make('status')->badge()->color(fn(string $state): string => match ($state) { 'completed' => 'success', 'pending' => 'warning', 'failed' => 'danger', default => 'primary',
                })->searchable(),
                TextColumn::make('created_at')->dateTime()->searchable(),
            ])

            ->actions([
                Tables\Actions\ViewAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Details')
                    ->columns(2)
                    ->schema([
                        Infolists\Components\TextEntry::make('transaction_type')->formatStateUsing(fn(string $state): string => ucfirst($state)),
                        Infolists\Components\TextEntry::make('reference'),
                        Infolists\Components\TextEntry::make('phone_number'),
                        Infolists\Components\TextEntry::make('description'),
                        Infolists\Components\TextEntry::make('price')->label('Amount')->money('NGN'),
                        Infolists\Components\TextEntry::make('status')->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'completed' => 'success',
                                'pending' => 'warning',
                                'failed' => 'danger',
                                default => 'primary',
                            }),
                        Infolists\Components\TextEntry::make('api_message')->label('API Response'),
                        Infolists\Components\TextEntry::make('created_at')->dateTime()->label('Date'),
                    ]),



            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAirtimeTransactions::route('/'),
            'create' => Pages\CreateAirtimeTransaction::route('/create'),
            'edit' => Pages\EditAirtimeTransaction::route('/{record}/edit'),
        ];
    }
}
