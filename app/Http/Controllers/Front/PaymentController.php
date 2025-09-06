<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PaymentController extends Controller
{
    public function create(Order $order)
    {
        return view('front.payments.create', compact('order'));
    }

    // todo i call this function by AJax
    public function createStripePaymentIntent(Order $order)
    {
        //todo i need get the relation collection (items)
        $ammount = $order->items->sum(function ($item) {
            return $item->price * $item->quantity;
        }) * 100;

        // $stripe = new \Stripe\StripeClient(config('services.stripe.secret_key'));
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret_key'));

        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => $ammount,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
        ]);

        // todo create payment
        try {
            $payment = new Payment();
            $payment->forceFill(
                [
                    'order_id' => $order->id,
                    'amount' => $paymentIntent->amount,
                    'currency' => $paymentIntent->currency,
                    'method' => 'stripe',
                    'status' => 'pending',
                    'transaction_id' => $paymentIntent->id,
                    'transaction_data' => json_encode($paymentIntent) // i need converting the string to JSON so i need using the method (_encode)
                ]
            )->save(); // if you do not using the fillable in model you can using this method (forceFill)
        } catch (QueryException $e) {
            echo $e->getMessage();
            return;
        }

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

    public function confirm(Request $request, Order $order)
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret_key'));

        $paymentIntent = $stripe->paymentIntents->retrieve(
            $request->query('payment_intent'),
            []
        );

        if ($paymentIntent->status == 'succeeded') {
            //@todo update payment
            try {
                $payment = Payment::where('order_id', $order->id)->first();
                $payment->forceFill(
                    [
                        'status' => 'completed',
                        'transaction_data' => json_encode($paymentIntent) // i need converting the string to JSON so i need using the method (_encode)
                    ]
                )->save(); // if you do not using the fillable in model you can using this method (forceFill)
            } catch (QueryException $e) {
                echo $e->getMessage();
                return;
            }

            // todo now you can doing any event or listener
            event('payment.created', $payment->id);
            return redirect()->route('home', ['status' => 'payment-succeeded']);
        }

        // if not success
        return redirect()->route('orders.payment.create', ['order' => $order->id, 'status' => $paymentIntent->status]);
    }
}
