<?php

namespace App\Controller;

use App\Manager\ProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function index(ProductManager $productManager): Response
    {
        return $this->render('default/index.html.twig', [
            'products' => $productManager->getProducts(),
        ]);
    }
}
