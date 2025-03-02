<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        // $wallet = $record->wallet_balance;
        // if ($record->wallet_balance > $data['wallet_balance']) {
        //     $record->withdraw($record->wallet_balance - $data['wallet_balance']);
        //     Transaction::create([
        //         'user_id' => $record->id,
        //         'transaction_type' => 'withdrawal',
        //         'price' => $wallet - $data['wallet_balance'],
        //         'amount_before' => $wallet,
        //         'amount_after' => $record->wallet_balance,
        //         'status' => 'completed'
        //     ]);
        // } elseif ($record->wallet_balance < $data['wallet_balance']) {
        //     $record->deposit($data['wallet_balance'] - $record->wallet_balance);
        //     Transaction::create([
        //         'user_id' => $record->id,
        //         'transaction_type' => 'wallet-top-up',
        //         'price' => $data['wallet_balance'] - $wallet,
        //         'amount_before' => $wallet,
        //         'amount_after' => $record->wallet_balance,
        //         'status' => 'completed'
        //     ]);
        // }
        $record->update($data);
        return $record;
    }


}
