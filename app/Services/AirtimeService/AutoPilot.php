<?php

namespace App\Services\AirtimeService;
use App\Models\Automation;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AutoPilot
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
            Notification::make()
                ->title('Something went wrong. Please try again later')
                ->danger()
                ->send();
        }

    }
}