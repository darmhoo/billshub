<?php

namespace App\Livewire;

use App\Livewire\Forms\UserForm;
use App\Models\User;
use App\Models\UserAccount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class CreateUser extends Component
{
    public UserForm $form;

    #[Title('User Register')]
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.create-user');
    }

    public function save()
    {
        dd('got here');

        $user = $this->form->store();
        $this->createWallet($user);
        event(new Registered($user));
        return $this->redirect('/app');
    }

    public static function createWallet(User $user): void
    {
        // dd(env());
        try {
            $token = Http::withHeaders(['Authorization' => 'Basic ' . base64_encode(env('MONNIFY_API_KEY') . ':' . env('MONNIFY_SECRET_KEY'))])
                ->post(env('MONNIFY_BASE_URL') . '/api/v1/auth/login', []);
            // dd($token->json());

            $account = Http::withHeaders(['Authorization' => 'Bearer ' . $token->json()['responseBody']['accessToken']])
                ->post(env('MONNIFY_BASE_URL') . '/api/v2/bank-transfer/reserved-accounts', [
                    'accountReference' => 'ref-' . Str::random(8),
                    'accountName' => $user->name . '-gbills',
                    'currencyCode' => 'NGN',
                    'contractCode' => '9117040590',
                    'customerName' => $user->name,
                    'bvn' => '22182596999',
                    'getAllAvailableBanks' => true,

                    'customerEmail' => $user->email
                ]);

            $accounts = $account->json()['responseBody']['accounts'];
            foreach ($accounts as $account) {
                UserAccount::create([
                    'user_id' => $user->id,
                    'bank_name' => $account['bankName'],
                    'bank_code' => $account['bankCode'],
                    'account_name' => $account['accountName'],
                    'account_number' => $account['accountNumber'],
                ]);
            }
            //code...
        } catch (\Throwable $th) {
            throw $th;
        }

    }
}
