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
            $data = [
                'customerName' => $customerName,
                'email' => $email,
                'identityType' => 'personal',
                'licenseNumber' => $bvn,
                'nonceStr' => strtotime(Carbon::now()) . Str::random(15),
                'requestTime' => Carbon::now()->valueOf(),
                'virtualAccountName' => 'PalmPay-' . 'Gbills-' . substr($customerName, 0, 5),
                'version' => 'V2.0',
            ];
            $sStr = urldecode(http_build_query($data));
            // dd($sStr);

            $hash = strtoupper(md5($sStr));

            // dd($this->virtualAccount->secret_key);
            $details = openssl_pkey_get_private($this->virtualAccount->secret_key);
            // dd($details);

            $signed = openssl_sign($hash, signature: $signature, private_key: $details, algorithm: OPENSSL_ALGO_SHA1);

            // dd(base64_encode($signature));
            $quota = env('QUOTAGUARDSTATIC_URL');
            $quota = parse_url($quota);
            $proxyUrl = $quota['host'] . ":" . $quota['port'];
            $proxyAuth = $quota['user'] . ":" . $quota['pass'];

            // dd($proxyAuth, $proxyUrl);

            $res = Http::withHeaders([
                'Content-Type' => 'application/json;charset=UTF-8',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->virtualAccount->api_key,
                'countryCode' => 'NG',
                'Signature' => base64_encode($signature),
            ])->withOptions([
                        'proxy' => $proxyUrl,
                        'proxyauth' => 'auth_basic',
                        'proxyuserpwd' => $proxyAuth
                    ])->post($this->virtualAccount->url . 'api/v2/virtual/account/label/create', $data);


            return $res->json();
        } catch (\Throwable $th) {
            throw $th;
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