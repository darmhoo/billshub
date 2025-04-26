<?php

namespace App\Services\VirtualAccount;
use App\Models\VirtualAccount;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Payvessel
{
    private $virtualAccount;
    public function __construct(VirtualAccount $virtualAccount)
    {
        $this->virtualAccount = $virtualAccount;
        // 
    }


    public function createVirtualAccount($bvn, $customerName, $email, $phone, $nin)
    {
        // 
        // dd(strtotime(Carbon::now()));
        try {
            $data = [
                'name' => $customerName,
                'email' => $email,
                'phoneNumber' => $phone,
                'nin' => $nin,
                'bvn' => $bvn,
                'bankcode' => ["999991", "120001"],
                'businessid' => $this->virtualAccount->merchant_code,
                'account_type' => "STATIC"
            ];
            dd($data);
            $virtualAccountName = 'PVS-' . 'Gbills-' . substr($customerName, 0, 5);



            $res = Http::withHeaders([
                'Content-Type' => 'application/json',
                'api-key' => 'Bearer ' . $this->virtualAccount->api_key,
                'api-secret' => 'Bearer ' . $this->virtualAccount->secret_key,
            ])->post($this->virtualAccount->url . 'pms/api/external/request/customerReservedAccount', $data);


            return $res->json();
        } catch (\Throwable $th) {
            return ['message' => $th->getMessage(), 'status' => 'error'];
        }

    }



}