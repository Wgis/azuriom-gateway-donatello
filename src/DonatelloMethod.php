<?php

namespace Azuriom\Plugin\Donatello;

use Azuriom\Models\User;
use Azuriom\Plugin\Shop\Cart\Cart;
use Azuriom\Plugin\Shop\Models\Payment;
use Azuriom\Plugin\Shop\Payment\PaymentMethod;
use Illuminate\Http\Request;

class DonatelloMethod extends PaymentMethod
{
    /**
     * The payment method id name.
     *
     * @var string
     */
    protected $id = 'donatello';

    /**
     * The payment method display name.
     *
     * @var string
     */
    protected $name = 'Donatello';

    public function startPayment(Cart $cart, float $amount, string $currency)
    {
        // if (! use_site_money()) {
        //     return redirect()->route('shop.cart.index')
        //         ->with('error', 'Этот метод не поддерживает прямые платежи');
        // } 
        
        $payment = $this->createPayment($cart, $amount, $currency);

        $auth_key = $this->gateway->data['auth-key'];
        $login = $this->gateway->data['login'];
        $pay_id = $payment->id;


        return redirect()->away("https://donatello.to/{$login}?&c={$pay_id}&a={$amount}&m=Добровольное пожертвование");
    }

    public function notification(Request $request, ?string $paymentId)
    {
        $auth_key = $this->gateway->data['auth-key'];
        $req_pay_id = intval($request->input('clientName'));
        $amount = intval($request->input('amount'));
     
        $payment = Payment::findOrFail($req_pay_id);
        $paymentID = intval($payment->id);
        $paymentPrice = intval($payment->price);

        if ($paymentID !== $req_pay_id) {
            
            logger()->info("Магазин | Несоответствие ID. Пришло {$req_pay_id} - ожидалось {$paymentID}");

            return $this->invalidPayment($payment, $paymentID, 'Несовпадение ID');
        }
        if ($paymentPrice > $amount) {
            
            logger()->info("Магазин | Несоответствие цены. Пришло {$amount} - ожидалось {$paymentPrice}");

            return $this->invalidPayment($payment, $paymentID, 'Invalid amount/id');
        }

        logger()->info("Магазин | Исполняем паймент {$req_pay_id}");
        $this->processPayment($payment, $req_pay_id);
        
        return redirect()->route('shop.payments.success', $this->id);
    }

   public function success(Request $request)
    {
        return redirect()->route('shop.home')->with('success', trans('messages.status.success'));
    }

    public function view()
    {
        return 'donatello::admin.donatello';
    }

    public function rules()
    {
        return [
            'auth-key' => ['required', 'string'],
            'login' => ['required', 'string'],
        ];
    }

    public function image()
    {
        return asset('plugins/donatello/img/donatello.svg');
    }

}
