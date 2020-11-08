<?php

namespace App\Manager;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\PasswordService;
use App\Services\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProductManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var StripeService
     */
    protected $stripeService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param StripeService $stripeService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        StripeService $stripeService
    ) {
        $this->em = $entityManager;
        $this->stripeService = $stripeService;
    }

    public function getProducts()
    {
        return $this->em->getRepository(Product::class)
            ->findAll();
    }

    /**
     * @param Product $product
     * @return \Stripe\PaymentIntent
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function intentSecret(Product $product)
    {
        $intent = $this->stripeService->paymentIntent($product);

        return $intent['client_secret'] ?? null;
    }

    /**
     * @param array $stripeParameter
     * @param Product $product
     * @return array|null
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function stripe(array $stripeParameter, Product $product)
    {
        $resource = null;
        $data = $this->stripeService->stripe($stripeParameter, $product);

        if ($data) {
            $resource = [
                'stripeBrand' => $data['charges']['data'][0]['payment_method_details']['card']['brand'],
                'stripeLast4' => $data['charges']['data'][0]['payment_method_details']['card']['last4'],
                'stripeId' => $data['charges']['data'][0]['id'],
                'stripeStatus' => $data['charges']['data'][0]['status'],
                'stripeToken' => $data['client_secret'],
            ];
        }

        return $resource;
    }

    /**
     * @param array $resource
     * @param Product $product
     * @param User $user
     * @throws \Exception
     */
    public function create_subscription(array $resource, Product $product, User $user)
    {
        $order = new Order();
        $order->setUser($user);
        $order->setProduct($product);
        $order->setReference(uniqid("", false));
        $order->setBrandStripe($resource['stripeBrand']);
        $order->setLast4Stripe($resource['stripeLast4']);
        $order->setIdChargeStripe($resource['stripeId']);
        $order->setStripeToken($resource['stripeToken']);
        $order->setStatusStripe($resource['stripeStatus']);
        $order->setUpdatedAt(new \DateTime());
        $order->setCreatedAt(new \DateTime());
        $this->em->persist($order);
        $this->em->flush();
    }
}