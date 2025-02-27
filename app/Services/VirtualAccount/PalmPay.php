<?php

namespace App\Services\VirtualAccount;
use App\Models\VirtualAccount;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PalmPay
{
    private $virtualAccount;
    public function __construct(VirtualAccount $virtualAccount)
    {
        $this->virtualAccount = $virtualAccount;
        // 
    }


    public function createVirtualAccount($bvn, $customerName, $email)
    {
        // 
        // dd(strtotime(Carbon::now()));
        try {
            $res = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->virtualAccount->api_key,
                'countryCode' => 'NG',
                'Signature' => $this->virtualAccount->secret_key,
            ])->post($this->virtualAccount->url . 'api/v2/virtual/account/label/create', [
                        'requestTime' => Carbon::now()->valueOf(),
                        'identityType' => 'personal',
                        'licenseNumber' => $bvn,
                        'virtualAccountName' => 'PalmPay-' . 'Gbills-' . substr($customerName, 0, 3),
                        'version' => 'V2.0',
                        'nonceStr' => strtotime(Carbon::now()) . Str::random(15),
                        'customerName' => $customerName,
                        'email' => $email

                    ]);


            return $res->json();
        } catch (\Throwable $th) {
            return $th;
        }

    }


    public function deleteAccount($phoneNumber, $planId, $network, $dataType)
    {
        //
        try {
            $dt = '';
            if ($dataType == 'AWOOF/GIFTING') {
                $dt = 'DIRECT GIFTING';
            } else if ($dataType == 'SME') {
                $dt = 'SME';
            } else {
                $dt = 'CORPORATE GIFTING';
            }
            $res = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->automation->api_key,
            ])->post($this->automation->base_url . '/v1/data', [
                        'phone' => $phoneNumber,
                        'planId' => $planId,
                        'networkId' => $network,
                        'dataType' => $dt,
                        'reference' => strtotime(Carbon::now()) . Str::random(15)
                    ]);


            return $res->json();


        } catch (\Throwable $th) {
            return Notification::make()
                ->title('Something went wrong. Please try again later')
                ->danger()
                ->send();
            //throw $th;
        }

    }

    public function airtimeCash()
    {

    }

    public function sendOtp($network, $number)
    {
        try {
            //code...
            $res = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->automation->api_key,
            ])->post($this->automation->base_url . '/v1/send-resend/auto-airtime-to-cash-otp', [
                        'senderNumber' => $number,
                        'network' => $network,

                    ]);
            return $res->json();
        } catch (\Throwable $th) {
            return Notification::make()
                ->title('Something went wrong. Please try again later')
                ->danger()
                ->send();
        }

    }

    public function verifyOtp($otp, $identifier)
    {
        try {
            //code...
            $res = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->automation->api_key,
            ])->post($this->automation->base_url . '/v1/verify/auto-airtime-to-cash-otp', [
                        'identifier' => $identifier,
                        'otp' => $otp,

                    ]);
            return $res->json();
        } catch (\Throwable $th) {
            Notification::make()
                ->title('Something went wrong. Please try again later')
                ->danger()
                ->send();
        }

    }
}