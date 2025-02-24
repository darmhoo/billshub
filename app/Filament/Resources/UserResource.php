<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Transaction;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $navigationGroup = 'User Management';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Select::make('account_type_id')
                    ->relationship(name: 'accountType', titleAttribute: 'name')

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(User::query()->role('user'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ToggleColumn::make('is_active')
                ,
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('wallet_balance')->money('NGN')
                    ->sortable(),
                Tables\Columns\TextColumn::make('accountType.name')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('Fund')
                    ->label('Fund')
                    ->modalSubheading('Your account balance: ₦' . auth()->user()->wallet_balance)
                    ->modalDescription('You are funding the user from your wallet Your account balance: ₦' . auth()->user()->wallet_balance)
                    ->form([
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('₦')
                            ->required()
                            ->default(0.00),
                        Forms\Components\TextInput::make('wallet_balance')
                            ->prefix('₦')
                            ->default(0.00)
                            ->disabled()
                    ])
                    ->action(function (User $user, $data) {
                        if (auth()->user()->wallet_balance < $data['amount']) {
                            Notification::make()
                                ->title('Insufficient funds')
                                ->body('You do not have enough funds to complete this transaction')
                                ->danger()
                                ->send();
                            return;
                        }
                        $user->deposit($data['amount']);
                        auth()->user()->withdraw($data['amount']);

                        Transaction::create([
                            'user_id' => $user->id,
                            'price' => $data['amount'],
                            'transaction_type' => 'Wallet-Top-Up',
                            'description' => auth()->user()->name . ' to ' . $user->name,
                            'amount_before' => $user->wallet_balance - $data['amount'],
                            'amount_after' => $user->wallet_balance,
                            'status' => 'completed',
                        ]);

                        Transaction::create([
                            'user_id' => auth()->id(),
                            'status' => 'completed',
                            'price' => $data['amount'],
                            'description' => auth()->user()->name . ' to ' . $user->name,
                            'amount_before' => auth()->user()->wallet_balance + $data['amount'],
                            'amount_after' => auth()->user()->wallet_balance,
                            'transaction_type' => 'Wallet-Debit',
                        ]);

                        Notification::make()
                            ->title('Account Funded Successfully')
                            ->success()
                            ->send();
                        return;

                    })
                    ->icon('heroicon-o-currency-dollar')
                    ->modal(),

                Action::make('Withdraw')
                    ->label('Withdraw')
                    ->modalSubheading('Your account balance: ₦' . auth()->user()->wallet_balance)
                    ->modalDescription(function (User $user) {
                        return 'You are withrawing from the user ' . $user->name . ' account ' . $user->wallet_balance;
                    })

                    ->form([
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('₦')
                            ->required()
                            ->default(0.00),
                    ])
                    ->action(function (User $user, $data) {
                        if ($user->wallet_balance < $data['amount']) {
                            Notification::make()
                                ->title('Insufficient funds')
                                ->body('There is not enough funds to complete this transaction')
                                ->danger()
                                ->send();
                            return;
                        }
                        $user->withdraw($data['amount']);
                        auth()->user()->deposit($data['amount']);
                        Transaction::create([
                            'user_id' => auth()->id(),
                            'status' => 'completed',
                            'price' => $data['amount'],
                            'description' => $user->name . ' to ' . auth()->user()->name,
                            'amount_before' => auth()->user()->wallet_balance - $data['amount'],
                            'amount_after' => auth()->user()->wallet_balance,
                            'transaction_type' => 'Wallet-Top-Up',
                        ]);
                        Transaction::create([
                            'user_id' => $user->id,
                            'price' => $data['amount'],
                            'transaction_type' => 'Wallet-Debit',
                            'description' => $user->name . ' to ' . auth()->user()->name,
                            'amount_before' => $user->wallet_balance + $data['amount'],
                            'amount_after' => $user->wallet_balance,
                            'status' => 'completed',
                        ]);

                        Notification::make()
                            ->title('Withdraw Funded Successfully')
                            ->success()
                            ->send();
                        return;

                    })
                    ->icon('heroicon-o-currency-dollar')
                    ->modal()




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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
