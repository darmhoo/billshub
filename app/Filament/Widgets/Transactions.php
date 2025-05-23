<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Infolists;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Transactions extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        // dd(Transaction::query()->where('id', auth()->user()->id));
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
                SelectFilter::make('transaction_type')
                    ->options([
                        'airtime' => 'Airtime',
                        'data' => 'Data',
                        'electricity' => 'Electricity',
                    ])
                    ->label('Type'),
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
            ->query(Transaction::query()->latest('created_at'))
            // ->striped()
            ->recordClasses(function (Model $record) {
                if ($record->amount_before > $record->amount_after) {
                    return 'bg-transparent';
                } else if ($record->amount_before < $record->amount_after) {
                    return 'bg-green-400';
                } else {
                    return '';
                }
            })
            ->emptyStateHeading('No Transactions Yet')
            ->columns([
                //
                TextColumn::make('user.name')->formatStateUsing(fn(string $state): string => ucfirst($state))->searchable(),
                TextColumn::make('transaction_type')->formatStateUsing(fn(string $state): string => ucfirst($state))->searchable()->label('Type'),
                TextColumn::make('reference')->searchable(),

                TextColumn::make('phone_number')->searchable()->label('Phone'),
                TextColumn::make('network')->formatStateUsing(fn(string $state): string => strtoupper($state))->searchable(),
                TextColumn::make('description')->searchable()->label('Description')->searchable()->copyable()
                    ->copyMessage('description copied')
                    ->copyMessageDuration(1500),

                TextColumn::make('price')->money('NGN')->label('Amount'),
                TextColumn::make('reference')->searchable()->copyable()
                    ->copyMessage('reference copied')
                    ->copyMessageDuration(1500),

                TextColumn::make('phone_number')->searchable()->label('Phone')->copyable()
                    ->copyMessage('phone number copied')
                    ->copyMessageDuration(1500),

                TextColumn::make('amount_before')->money('NGN')->label('Bal Before'),
                TextColumn::make('amount_after')->money('NGN')->label('Bal After'),
                TextColumn::make('status')->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'primary',
                    }),
                TextColumn::make('created_at')->dateTime()->searchable(),
            ])

            ->actions([
                Tables\Actions\ViewAction::make()
                    ->infolist(function (Infolist $infolist) {
                        return $infolist

                            ->schema([
                                Infolists\Components\TextEntry::make('transaction_type')->formatStateUsing(fn(string $state): string => ucfirst($state))->label('Type'),
                                Infolists\Components\TextEntry::make('reference'),
                                Infolists\Components\TextEntry::make('phone_number'),
                                Infolists\Components\TextEntry::make('price')->label('Amount')->money('NGN'),
                                Infolists\Components\TextEntry::make('status')->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'completed' => 'success',
                                        'pending' => 'warning',
                                        'failed' => 'danger',
                                        default => 'primary',
                                    }),
                                Infolists\Components\TextEntry::make('api_message')->label('API Response'),
                                Infolists\Components\TextEntry::make('created_at')->dateTime(),
                            ]);





                    }),
            ], position: ActionsPosition::BeforeCells);
    }
}
