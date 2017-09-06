<?php

namespace App\Models;

use App\Traits\EhdaUserTrait;
use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use SoapClient;

class Transaction extends Model
{
    use TahaModelTrait, SoftDeletes, EhdaUserTrait;

    protected $guarded = ['id'];
    public static $meta_fields     = [
        'user_ip_address',
    ];

    protected $merchant_id = '465b79d2-8fda-11e7-8975-005056a205be';
    protected $call_back_url = null;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public static function builder()
    {
        $model = new self;

        $model->amount_payable = 0;
        $model->amount_paid = 0;
        $model->redirect_url = null;
        $model->tracking_number = null;
        $model->payment_status = 0;
        $model->status = 0;
        $model->call_back_url = route('bank-process', ['tracking_number' => $model->tracking_number]);

        return $model;
    }

    public function invoice($amount, $redirect_url)
    {
        if ($amount > 0)
            $this->amount_payable = $amount;

        if (isset($redirect_url))
            $this->redirect_url = $redirect_url;

        return $this;
    }

    public function getTracking()
    {
        $transaction = $this->storeData();
        if ($transaction)
        {
            return $this->tracking_number = $transaction->tracking_number;
        }
        else
        {
            return false;
        }
    }

    public function payment()
    {
        if ($this->getTracking())
            return $this->fire();
        else
            return false;
    }

    public function fire($tracking_number = null)
    {
        $transaction = null;

        if (is_null($tracking_number))
        {
            if ($this->tracking_number > 0)
                $transaction = $this->tracking_number;
        }
        else
        {
            $transaction = $tracking_number;
        }

        $transaction = self::findBySlug($transaction, 'tracking_number');

        if (! $transaction)
            return false;

        if ($transaction->payment_status != 0 or $transaction->status != 0)
            return false;

        return $this->zarinpal_payment_request($transaction);
    }

    public function verify()
    {
        if ($this->payment_status == 100 and $this->status == 1)
        {
            $this->update([
                'status' => 3,
            ]);
            return $this->zarinpal_payment_verify();
        }
        else
        {
            return false;
        }
    }

    public function check()
    {
        if ($this->payment_status == 100 and $this->status == 10 and $this->amount_payable == $this->amount_paid)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    private function zarinpal_payment_request($transaction)
    {
        $client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

        $result = $client->PaymentRequest(
            [
                'MerchantID' => $this->merchant_id,
                'Amount' => $transaction->amount_payable,
                'CallbackURL' => $this->call_back_url . '/' . $transaction->tracking_number,
                'Description' => trans('front.invoice_payment') . ' ' . $transaction->tracking_number
            ]
        );

        if ($result->Status == 100)
        {
            $transaction->update([
                'authority_code' => $result->Authority,
                'payment_status' => $result->Status,
                'status' => 1,
            ]);
            return 'https://www.zarinpal.com/pg/StartPay/'. $result->Authority .'/ZarinGate';
        }
        else
        {
            $transaction->update([
                'payment_status' => $result->Status,
                'status' => 2,
            ]);
            return false;
        }
    }

    public function zarinpal_payment_verify()
    {
        $client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

        $result = $client->PaymentVerification(
            [
                'MerchantID' => $this->merchant_id,
                'Authority' => $this->authority_code,
                'Amount' => $this->amount_payable,
            ]
        );

        if ($result->Status == 100)
        {
            $this->update([
                'amount_paid' => $this->amount_payable,
                'status' => 10,
                'ref_id' => $result->RefID,
            ]);
            return true;
        }
        else
        {
            $this->update([
                'status' => 4,
                'payment_status' => $result->Status,
            ]);
            return false;
        }
    }

    private function storeData()
    {
        if ($this->amount_payable > 0 and isset($this->redirect_url))
        {
            $data = [
                'amount_payable' => $this->amount_payable,
                'amount_paid' => 0,
                'tracking_number' => $this->generateTracking(),
                'payment_status' => $this->payment_status,
                'redirect_url' => $this->redirect_url,
                'user_ip_address' => Request::ip(),
                'user_id' => user()->id,
            ];

            $store = Transaction::store($data);

            if ($store)
            {
                return self::find($store);
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    private function generateTracking()
    {
        $tracking_number = 0;

        for ($i = 0; $i < 100; $i++)
        {
            $tracking_number = number_random(10);
            $tracking_exists = self::where('tracking_number', $tracking_number)->count();
            if (! $tracking_exists)
            {
                break;
            }
        }

        return $tracking_number;
    }

    public function getRedirectorAttribute()
    {
        return $this->redirect_url . '?tracking=' . $this->tracking_number;
    }
}

/*
 * Status:
 *  0  => created invoice
 *  1  => transaction ready and redirect user to bank
 *  2  => transaction not ready for payment
 *  3  => transaction paid and in verify process
 *  4  => transaction not correctly paid and not verified from bank
 *  10 => transaction complete and verify by bank
 *
 *
 */