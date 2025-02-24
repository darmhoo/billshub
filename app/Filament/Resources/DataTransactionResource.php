<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataTransactionResource\Pages;

use App\Models\Transaction;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Infolists;

class DataTransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationGroup = 'Data';

    protected static ?string $navigationLabel = 'Data Transactions';



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
            ->query(Transaction::query()->where('transaction_type', 'data')->latest('created_at'))

            ->columns([
                //
                TextColumn::make('user.name')->formatStateUsing(fn(string $state): string => ucfirst($state))->searchable()->copyable()
                    ->copyMessage('Type copied')
                    ->copyMessageDuration(1500),

                TextColumn::make('transaction_type')->formatStateUsing(fn(string $state): string => ucfirst($state))->searchable()->label('Type'),
                TextColumn::make('reference')->searchable()->copyable()
                    ->copyMessage('reference copied')
                    ->copyMessageDuration(1500),

                TextColumn::make('phone_number')->searchable()->label('Phone')->copyable()
                    ->copyMessage('phone number copied')
                    ->copyMessageDuration(1500),


                TextColumn::make('description')->formatStateUsing(fn(string $state): string => strtoupper($state))->searchable()->copyable()
                    ->copyMessage('Type copied')
                    ->copyMessageDuration(1500),

                TextColumn::make('network')->formatStateUsing(fn(string $state): string => strtoupper($state))->searchable(),
                TextColumn::make('price')->money('NGN')->label('Amount'),
                TextColumn::make('amount_before')->money('NGN')->label('Bal Before'),
                TextColumn::make('amount_after')->money('NGN')->label('Bal After'),
                TextColumn::make('status')->badge()->color(fn(string $state): string => match ($state) { 'completed' => 'success', 'pending' => 'warning', 'failed' => 'danger', default => 'primary',
                })->searchable(),
                TextColumn::make('created_at')->dateTime()->searchable(),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDataTransactions::route('/'),
            'create' => Pages\CreateDataTransaction::route('/create'),
            // 'edit' => Pages\EditDataTransaction::route('/{record}/edit'),
        ];
    }
}
