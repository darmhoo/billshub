<?php

namespace App\Services\AirtimeService;
use App\Models\Automation;
use App\Models\ElectricityProvider;
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

    public function verifyMeterNumber($meter_number, $serviceId, $type)
    {

        try {
            $res = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'api-key' => $this->automation->api_key,
                'secret-key' => $this->automation->secret_key,
            ])->post($this->automation->base_url . '/merchant-verify', [
                        'type' => $type,
                        'billersCode' => $meter_number,
                        'serviceID' => ElectricityProvider::where('id', $serviceId)->first()->service_id,
                    ]);

            return $res->json();
        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function payElectricity($serviceId, $meter_number, $amount, $type, $phone)
    {
        // dd($serviceId, $meter_number, $amount, $type, $phone);
        try {
            $res = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'api-key' => $this->automation->api_key,
                'secret-key' => $this->automation->secret_key,
            ])->post($this->automation->base_url . '/pay', [
                        'billersCode' => $meter_number,
                        'amount' => $amount,
                        'serviceID' => ElectricityProvider::where('id', $serviceId)->first()->service_id,
                        'request_id' => strtotime(Carbon::now()) . Str::random(15),
                        'variation_code' => $type,
                        'phone' => $phone
                    ]);

            return $res->json();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}