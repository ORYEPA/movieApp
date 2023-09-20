<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order')]
    public function orderIndex(Request $request)
    {

        return $this->render("order/index.html.twig", [

        ]);
    }
    #[Route('/cartProducts')]
    public function cartProducts(Request $request,
                                 EntityManagerInterface $entityManager,
                                 LoggerInterface    $logger)
    {
        $userId = $this->getUser()->getId();

        $connection = $entityManager->getConnection();

        $product = $connection->fetchAllAssociative("
            SELECT *
            FROM carrito 
            join product on product.id=carrito.id_product
             where user_id = $userId  "
        );

        return $this->json([

            "productos" => $product,
        ]);
    }

}