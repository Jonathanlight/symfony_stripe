<?php

namespace App\Controller\Middle;

use App\Entity\Product;
use App\Manager\ProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/user/payment/{id}/show", name="payment", methods={"GET", "POST"})
     * @param Product $product
     * @return Response
     */
    public function payment(Product $product, ProductManager $productManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        return $this->render('user/payment.html.twig', [
            'user' => $this->getUser(),
            'intentSecret' => $productManager->intentSecret($product),
            'product' => $product
        ]);
    }

    /**
     * @Route("/user/subscription/{id}/paiement/load", name="subscription_paiement", methods={"GET", "POST"})
     * @param Product $product
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function subscription(
        Product $product,
        Request $request,
        ProductManager $productManager
    ){
        $user = $this->getUser();

        if($request->getMethod() === "POST") {
            $resource = $productManager->stripe($_POST, $product);

            if(null !== $resource) {
                $productManager->create_subscription($resource, $product, $user);

                return $this->render('user/reponse.html.twig', [
                    'product' => $product
                ]);
            }
        }

        return $this->redirectToRoute('payment', ['id' => $product->getId()]);
    }

    /**
     * @Route("/user/payment/orders", name="payment_orders", methods={"GET"})
     * @param ProductManager $productManager
     * @return Response
     */
    public function payment_orders(ProductManager $productManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }

        return $this->render('user/payment_story.html.twig', [
            'user' => $this->getUser(),
            'orders' => $productManager->getOrders($this->getUser()),
            'sumOrder' => $productManager->countSoldeOrder($this->getUser()),
        ]);
    }
}
