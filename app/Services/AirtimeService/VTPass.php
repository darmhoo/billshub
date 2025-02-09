<?php

namespace App\Services\AirtimeService;
use App\Models\Automation;
use App\Models\Network;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class VTPass
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
            'api-key' => $this->automation->api_key,
            'secret-key' => $this->automation->secret_key,
        ])->post($this->automation->base_url . '/pay', [
                    'phone' => $phoneNumber,
                    'amount' => $amount,
                    'serviceID' => Network::where('id', $network)->first()->name,
                    'request_id' => strtotime(Carbon::now()) . Str::random(15)

                ]);

        return $res->json();
    }
}