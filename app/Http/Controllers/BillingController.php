<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use Stripe\StripeClient;

class BillingController extends Controller
{
    public function checkout(Request $request)
    {
        $user = $request->user();
        $stripe = new StripeClient(config('cashier.secret'));

        $checkoutSession = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => 'Premium Plan'],
                    'unit_amount' => 1000, // $10.00
                ],
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('checkout.success'),
            'cancel_url' => route('checkout.cancel'),
            'customer' => $user->createOrGetStripeCustomer()->id,
        ]);

        return response()->json(['url' => $checkoutSession->url]);
    }
}
