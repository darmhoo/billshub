<?php

namespace App\Filament\App\Widgets;

use App\Models\Transaction;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

class TransactionsWidget extends BaseWidget
{
    public function table(Table $table): Table
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
}
