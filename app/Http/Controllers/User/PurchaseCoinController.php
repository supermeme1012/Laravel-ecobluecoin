<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class PurchaseCoinController extends Controller {
    public function purchaseCoin() {
        $pageTitle = 'Purchase Coin';
        return view($this->activeTemplate . 'user.purchase.coin', compact('pageTitle'));
    }

    public function purchaseConfirm(Request $request) {

        $request->validate([
            'amount'         => 'required|numeric|gt:0',
            'payment_status' => 'required|integer|in:1,2',
        ]);

        $general   = gs();
        $basePrice = $general->coin_rate / $general->cur_rate;
        $payAmount = getAmount($request->amount * $basePrice, 2);

        if ($request->payment_status == 2) {
            session()->put('coin_amount', [
                'coin'  => $request->amount,
                'price' => $payAmount,
            ]);
            return to_route('user.payment');
        }

        $user = auth()->user();

        if ($payAmount > $user->balance) {
            $notify[] = ['error', 'Insufficient balance in your account'];
            return back()->withNotify($notify);
        }

        $user->balance -= getAmount($payAmount);
        $user->coin += $request->amount;
        $user->save();

        $trx = getTrx();
        // if(getAmount($request->amount)>100000){
        //     dd( getAmount($request->amount));

        // }
       
        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = getAmount($payAmount);
        $transaction->charge       = 0;
        $transaction->post_balance = getAmount($user->balance);
        $transaction->trx_type     = '-';
        $transaction->trx          = $trx;
        $transaction->remark       = 'purchase';
        $transaction->details      = $payAmount . ' ' . $general->cur_text . ' subtract for coin purchased';
        $transaction->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->coin_status  = 1;
        $transaction->amount       = getAmount($request->amount);
        $transaction->charge       = 0;
        $transaction->post_balance = getAmount($user->coin);
        $transaction->trx_type     = '+';
        $transaction->trx          = $trx;
        $transaction->remark       = 'purchase';
        $transaction->details      = 'You have purchased ' . showAmount($request->amount) . ' ' . $general->coin_code;
        $transaction->save();

           // bonus
           $level1 = User::where('id', $user->id)->first();
        
           if ($level1->ref_by != 0 && getAmount($request->amount)>=100000){
            
               $level1_id = $level1->ref_by;
               $transaction               = new Transaction();
               $transaction->user_id      = $level1_id;
               $transaction->receiver_id  = $user->id;
               $transaction->coin_status  = 1;
               $transaction->amount       = getAmount($request->amount) * $general->level1_percentage / 100;
               $transaction->charge       = 0;
               $transaction->post_balance = getAmount($level1->coin);
               $transaction->trx_type     = '+';
               $transaction->trx          = $trx;
               $transaction->remark       = 'bonus';
               $transaction->details      = 'You have received bonus' . showAmount($request->amount * $general->level1_percentage / 100) . ' ' . $general->coin_code .' from '. $user->username;
               $transaction->save();
               $level1_data               = User::find($level1->ref_by);
               $level1_data->coin         = ($request->amount * $general->level1_percentage / 100) + ($level1_data->coin);
               $level1_data->save(); 
               
               $level2 = User::where('id', $level1->ref_by)->first();
               if ($level2->ref_by != 0){
                   $level2_id = $level2->ref_by;
                //    dd($level2_id, $level1_id);   
                   $transaction               = new Transaction();
                   $transaction->user_id      = $level2_id;
                   $transaction->receiver_id  = $user->id;
                   $transaction->coin_status  = 1;
                   $transaction->amount       = getAmount($request->amount) * $general->level2_percentage / 100;
                   $transaction->charge       = 0;
                   $transaction->post_balance = getAmount($level2->coin);
                   $transaction->trx_type     = '+';
                   $transaction->trx          = $trx;
                   $transaction->remark       = 'bonus';
                   $transaction->details      = 'You have received bonus' . showAmount($request->amount * $general->level2_percentage / 100) . ' ' . $general->coin_code .' from '. $user->username;
                   $transaction->save();
                   $level2_data               = User::find($level2->ref_by);
                   $level2_data->coin         = ($request->amount * $general->level2_percentage / 100) + ($level2_data->coin);
                   $level2_data->save(); 
                   
                   $level3 = User::where('id', $level2->ref_by)->first();
   
                   if ($level3->ref_by != 0){
                       $level3_id = $level3->ref_by;
                       $transaction               = new Transaction();
                       $transaction->user_id      = $level3_id;
                       $transaction->receiver_id  = $user->id;
                       $transaction->coin_status  = 1;
                       $transaction->amount       = getAmount($request->amount) * $general->level3_percentage / 100;
                       $transaction->charge       = 0;
                       $transaction->post_balance = getAmount($level3->coin);
                       $transaction->trx_type     = '+';
                       $transaction->trx          = $trx;
                       $transaction->remark       = 'bonus';
                       $transaction->details      = 'You have received bonus' . showAmount($request->amount * $general->level3_percentage / 100) . ' ' . $general->coin_code .' from '. $user->username;
                       $transaction->save();
                       $level3_data               = User::find($level3->ref_by);
                       $level3_data->coin         = ($request->amount * $general->level3_percentage / 100) + ($level3_data->coin);
                       $level3_data->save(); 
                    //    dd("hello1234567");
                   }
               }
           }

        notify($user, 'PURCHASE_COMPLETED', [
            'user_name'     => $user->username,
            'method_name'   => 'User balance',
            'amount'        => getAmount($request->amount),
            'charge'        => getAmount(0),
            'paid_amount'   => showAmount($payAmount),
            'post_balance'  => showAmount($user->balance),
            'coin'          => showAmount($user->coin),
            'site_currency' => $general->cur_text,
            'coin_code'     => $general->coin_code,
            'trx'           => $trx,
        ]);

        $notify[] = ['success', 'Coin purchased successfully'];
        return back()->withNotify($notify);
    }

}
