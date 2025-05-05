<?php

namespace App\Filament\App\Pages;

use App\Models\User;
use App\Models\UserAccount;
use App\Models\VirtualAccount;
use App\Services\VirtualAccount\PalmPay;
use App\Services\VirtualAccount\Payvessel;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class FundWallet extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-wallet';
    protected static ?string $navigationGroup = 'Wallet';


    protected static string $view = 'filament.app.pages.fund-wallet';

    protected static ?string $title = 'Fund Wallet';

    protected static ?int $navigationSort = 2;

    public ?string $bvn = null;
    public ?string $nin = null;


    protected function getViewData(): array
    {
        $user = User::find(Auth::user()->id);
        // dd($user);
        return [
            'bank_accounts' => $user->accounts
        ];
    }

    public function getVirtualAccount()
    {
        $user = User::find(Auth::user()->id);

        $vauto = VirtualAccount::where('name', 'Payvessel')->first();
        $accountService = new Payvessel($vauto);
        return Action::make('getVirtualAccount')
            ->label('Get Account')
            ->modalHeading('Virtual Account')
            ->modalWidth('max-w-xl')
            ->modalSubmitActionLabel('Submit')
            ->form([
                TextInput::make('bvn')
                    ->label('Bank Verification Number')
                    ->placeholder('Bank Verification Number')
                    ->required()
                    ->numeric()
                    ->length(11),
                TextInput::make('nin')
                    ->label('National Identification Number')
                    ->placeholder('National Identification Number')
                    ->required()
                    ->numeric()
                    ->length(11),
            ])
            ->action(function ($data) use ($accountService) {
                $res = $accountService->createVirtualAccount($data['bvn'], Auth::user()->name, Auth::user()->email, Auth::user()->phone_number, $data['nin']);
                // dd($res);
                if ($res['status'] == true) {
                    // dd($res);
                    $account = UserAccount::where('user_id', Auth::user()->id)->where('account_number', $res['banks'][0]['accountNumber'])->first();
                    if ($account) {
                        Notification::make()
                            ->title('Account Alredy Exists')
                            ->danger()
                            ->send();
                    }
                    UserAccount::create([
                        'user_id' => Auth::user()->id,
                        'bank_name' => '9Payment Service Bank',
                        'bank_code' => $res['banks'][0]['bankCode'],
                        'account_name' => $res['banks'][0]['accountName'],
                        'account_number' => $res['banks'][0]['accountNumber'],
                    ]);
                    Notification::make()
                        ->title('Wallet Created Successfully')
                        ->success()
                        ->send();
                } else {
                    Notification::make()
                        ->title('Wallet Creation Failed')
                        ->danger()
                        ->send();
                }


            });



    }

    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //         AccountFundingList::class
    //     ];
    // }
}
