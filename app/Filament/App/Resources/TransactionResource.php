<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\TransactionResource\Pages;
use App\Filament\App\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    // protected static ?string $model = Transaction::query->where('user_id', '=', auth()->user()->id);

    protected static ?string $navigationIcon = 'heroicon-o-numbered-list';

    protected static ?string $navigationGroup = 'Wallet';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        // dd(Transaction::query()->where('id', auth()->user()->id));
        return $table
            ->query(Transaction::query()->where('user_id', auth()->user()->id)->latest('created_at'))
            ->striped()
            ->recordClasses(function (Model $record) {
                if ($record->amount_before > $record->amount_after) {
                    return 'bg-red-100';
                } else {
                    return 'bg-green-100';
                }
            })
            ->emptyStateHeading('No Transactions Yet')
            ->columns([
                //
                TextColumn::make('id'),
                TextColumn::make('transaction_type')->searchable(),
                TextColumn::make('price')->money('NGN')->label('Amount'),
                TextColumn::make('amount_before')->money('NGN')->label('Balance Before'),
                TextColumn::make('amount_after')->money('NGN')->label('Balance After'),
                TextColumn::make('status')->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'primary',
                    }),
                TextColumn::make('created_at')->dateTime()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
