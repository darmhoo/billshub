<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ElectricityTransactionResource\Pages;

use App\Models\Transaction;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class ElectricityTransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationGroup = 'Electricity';

    protected static ?string $navigationLabel = 'Electricity Transactions';



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
            ->query(Transaction::query()->where('transaction_type', 'electricity')->latest())
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
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListElectricityTransactions::route('/'),
            'create' => Pages\CreateElectricityTransaction::route('/create'),
            'edit' => Pages\EditElectricityTransaction::route('/{record}/edit'),
        ];
    }
}
