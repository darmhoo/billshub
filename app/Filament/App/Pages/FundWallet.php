<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Widgets\AccountFundingList;
use App\Models\User;
use App\Models\UserAccount;
use App\Models\VirtualAccount;
use App\Services\VirtualAccount\PalmPay;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;

class FundWallet extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-wallet';
    protected static ?string $navigationGroup = 'Wallet';


    protected static string $view = 'filament.app.pages.fund-wallet';

    protected static ?string $title = 'Fund Wallet';

    protected static ?int $navigationSort = 2;

    public ?string $bvn = null;


    protected function getViewData(): array
    {
        $user = User::find(auth()->user()->id);
        // dd($user);
        return [
            'bank_accounts' => $user->accounts
        ];
    }

    public function getVirtualAccount()
    {
        $vauto = VirtualAccount::where('name', 'Palmpay')->first();
        $accountService = new PalmPay($vauto);
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
            ])
            ->action(function ($data) use ($accountService) {
                $res = $accountService->createVirtualAccount($data['bvn'], auth()->user()->name, auth()->user()->email);
                // dd($res);
                if ($res['status'] == true) {
                    UserAccount::create([
                        'user_id' => auth()->user()->id,
                        'bank_name' => 'palmpay',
                        'bank_code' => $res['data']['accountReference'],
                        'account_name' => $res['data']['virtualAccountName'],
                        'account_number' => $res['data']['virtualAccountNo'],
                    ]);
                    $this->notify('success', 'Virtual Account Created Successfully');
                } else {
                    $this->notify('error', 'An error occurred');
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
