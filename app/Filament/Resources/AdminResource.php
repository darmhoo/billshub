<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\RelationManagers;
use App\Models\Admin;
use App\Models\Transaction;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Permission;

class AdminResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationLabel = 'Admin';



    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->revealable(),
                Forms\Components\Toggle::make('is_active'),
                Forms\Components\TextInput::make('phone_number')
                    ->required()
                    ->length(11)
                    ->numeric(),
                Forms\Components\Select::make('permissions')
                    ->multiple()
                    ->options(Permission::all()->pluck('name', 'id'))
                    ->searchable()

                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(User::query()->role(['admin', 'super-admin']))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')
                ,
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
                            ->placeholder('3,000')
                        // Forms\Components\TextInput::make('description')

                    ])
                    ->action(function (User $user, $data) {
                        if (auth()->user()->id == $user->id) {
                            Notification::make()
                                ->title('Invalid Action')
                                ->body('You cannot fund your own account')
                                ->danger()
                                ->send();
                            return;
                        }
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
                            'user_id' => auth()->id(),
                            'status' => 'completed',
                            'price' => $data['amount'],
                            'description' => auth()->user()->name . ' to ' . $user->name,
                            'amount_before' => auth()->user()->wallet_balance + $data['amount'],
                            'amount_after' => auth()->user()->wallet_balance,
                            'transaction_type' => 'Wallet-Debit',
                        ]);
                        Transaction::create([
                            'user_id' => $user->id,
                            'price' => $data['amount'],
                            'transaction_type' => 'Wallet-Top-Up',
                            'description' => auth()->user()->name . ' to ' . $user->name,
                            'amount_before' => $user->wallet_balance - $data['amount'],
                            'amount_after' => $user->wallet_balance,
                            'status' => 'completed',
                        ]);

                        Notification::make()
                            ->title('Account Funded Successfully')
                            ->success()
                            ->send();
                        return;

                    })
                    ->icon('heroicon-o-currency-dollar')
                    ->modal(),


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
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
