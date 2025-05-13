<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\VirtualAccount;
use Illuminate\Http\Request;

class VirtualAccountController extends Controller
{
    //
    public function verifyPaymentPV(Request $request)
    {
        // dd($request->all());
        $data = $request->all();
        // dd($payload);
        if (empty($data)) {
            return response()->json([
                "response" => "Payload is empty"
            ]);
        }
        // $payvessel_signature = $request['HTTP_PAYVESSEL_HTTP_SIGNATURE'];
        // $ip_address = request()->ip();
        // $api_secret = VirtualAccount::where('name', 'Payvessel')->first()->secret_key;
        // $ipWhiteList = ['3.225.23.38'];
        // $hash_key = hash_hmac('sha256', $payload, $api_secret);
        // if ($payvessel_signature !== $hash_key || !in_array($ip_address, $ipWhiteList)) {
        //     return response()->json([
        //         "response" => "Permission denied, invalid hash or ip address."
        //     ]);
        // }

        // $data = json_decode($payload, true);
        $amount = $data['order']['amount'];
        $settlementAmount = $data['order']['settlement_amount'];
        $fee = $data['order']['fee'];
        $reference = $data['transaction']['reference'];
        $description = $data['order']['description'];


        $transaction = Transaction::where('reference', $reference)->first();
        if ($transaction) {
            return response()->json([
                "response" => "Transaction already exists"
            ], 200);
        }

        $user = User::where('email', $data['customer']['email'])->first();
        $user->deposit($settlementAmount);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'price' => $settlementAmount,
            'amount_before' => $user->balance - $settlementAmount,
            'amount_after' => $user->balance,
            'reference' => $reference,
            'description' => $description,
            'status' => 'completed',
            'transaction_type' => 'fund-wallet',
            'api_message' => 'success',
        ]);
        // if ($transaction && $request['data']['content']['transactions']['status']) {
        //     $transaction->update([
        //         'status' => 'completed',
        //         'response' => $request['data']['content']['transactions']
        //     ]);
        //     return response()->json([
        //         "response" => "success"
        //     ]);
        // }
        return response()->json([
            "message" => "success",
            "data" => $data
        ], 200);
    }
}
