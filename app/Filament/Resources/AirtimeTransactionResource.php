<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AirtimeTransactionResource\Pages;
use App\Filament\Resources\AirtimeTransactionResource\RelationManagers;
use App\Models\AirtimeTransaction;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
            ->query(Transaction::query()->where('transaction_type', 'airtime')->latest())
            ->columns([
                //
                TextColumn::make('transaction_type')->formatStateUsing(fn(string $state): string => ucfirst($state))->searchable(),
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
            'index' => Pages\ListAirtimeTransactions::route('/'),
            'create' => Pages\CreateAirtimeTransaction::route('/create'),
            'edit' => Pages\EditAirtimeTransaction::route('/{record}/edit'),
        ];
    }
}
