<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use SoapClient;

class PaymentController extends Controller
{
    public function bank_process($tracking_number)
    {
        $status = Input::get('Status');
        $authority = Input::get('Authority');

        $transaction = Transaction::where('tracking_number', $tracking_number)
            ->where('authority_code', $authority)
            ->first();
        if (! $transaction)
            return view('errors.404');

        if ($transaction->status != 1)
        return view('errors.404');

        if ($status == 'OK')
        {
            $transaction->verify();
            return redirect($transaction->redirector);
        }
        else
        {
            $transaction->update([
                'status' => 4,
            ]);

            return redirect($transaction->redirector);
        }
    }
}
