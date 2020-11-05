<?php

namespace App\Services;

use App\Entity\Order;
use App\Entity\Product;

class StripeService
{
    /**
     * @var string
     */
    private $privateKey;

    public function __construct()
    {
        if ($_ENV['APP_ENV'] == 'dev') {
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
        } else {
            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_LIVE'];
        }
    }

    /**
     * @param Product $product
     * @return \Stripe\PaymentIntent
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function paymentIntent(Product $product)
    {
        \Stripe\Stripe::setApiKey($this->privateKey);

        return \Stripe\PaymentIntent::create([
            'amount' => $product->getPrice() * 100,
            'currency' => Order::DEVISE,
            'payment_method_types' => ['card'],
        ]);
    }

    /**
     * @param $amount
     * @param string $currency
     * @param string $description
     * @param array $stripeParameter
     * @return \Stripe\PaymentIntent|null
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function paiement($amount, string $currency, string $description, array $stripeParameter)
    {
        \Stripe\Stripe::setApiKey($this->privateKey);
        $payment_intent = null;

        // stripeIntentId stripeIntentPaymentMethod stripeIntentStatus subscription

        if (isset($stripeParameter['stripeIntentId'])) {
            $payment_intent = \Stripe\PaymentIntent::retrieve(
                $stripeParameter['stripeIntentId']
            );
        }

        if ($stripeParameter['stripeIntentStatus'] == "succeeded") {

        } else {
            $payment_intent->cancel();
        }

        return $payment_intent;
    }

    /**
     * @param array $stripeParameter
     * @param Product $product
     * @return \Stripe\Charge|\Stripe\PaymentIntent
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function stripe(array $stripeParameter, Product $product)
    {
        return $this->paiement(
            $product->getPrice() * 100,
            Order::DEVISE,
            $product->getName(),
            $stripeParameter
        );
    }
}