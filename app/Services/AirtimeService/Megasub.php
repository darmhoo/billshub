<?php

namespace App\Services\AirtimeService;
use App\Models\Automation;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Megasub
{
    private $automation;
    public function __construct(Automation $automation)
    {
        $this->automation = $automation;
        // 
    }


    public function sendAirtime($phoneNumber, $amount, $network)
    {
        // 
        $res = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->automation->api_key,
        ])->post($this->automation->base_url . '/v1/airtime', [
                    'phone' => $phoneNumber,
                    'amount' => $amount,
                    'networkId' => $network,
                    'airtimeType' => 'VTU',
                    'reference' => strtotime(Carbon::now()) . Str::random(15)
                ]);


        return $res->json();
    }


    public function buyData($phoneNumber, $planId, $network, $dataType)
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
            ])->post($this->automation->base_url . '?action=buy_data', [
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