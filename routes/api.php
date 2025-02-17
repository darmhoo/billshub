<?php

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/monnify-fund-wallet', function (Request $request) {

});

Route::get('update-transaction', function (Request $request) {
    if($request['type'] === 'transaction-update'){
        $transaction = Transaction::where('reference', $request['requestId']);
        if($transaction && $request['data']['content']['transactions']['status'])
        return response()->json([
            "response"=> "success"
        ]);
    }

    

});