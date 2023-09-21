<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
             where user_id = $userId and order_id is null "
        );
        $total = $connection->fetchOne("
            SELECT  format( sum(quantity)* (price), 2)
            FROM carrito 
            join product on product.id=carrito.id_product
             where user_id = $userId and order_id is null"
        );

        return $this->json([

            "productos" => $product,
            "total"=> $total
        ]);
    }
    #[Route('/createOrder')]
    public function createOrder(Request $request,
                                EntityManagerInterface $entityManager,
                                LoggerInterface        $logger): Response
    {
        $userId = $this->getUser()->getId();
        $status=10;
        $connection = $entityManager->getConnection();
        $product = $connection->fetchAllAssociative("
            Select * from carrito where user_id= $userId
                                    and order_id is null "
        );

        if(count($product) > 0){
            $connection->executeQuery("
                INSERT INTO `order` (date, status_id, user_id)
                values (utc_timestamp(), $status, $userId)
                ");
            $order=$connection->fetchOne("select last_insert_id()");

            $connection->executeQuery("
                        Update carrito set order_id = $order
                        where user_id= $userId
                        and order_id is null ");
            return $this->redirect("/order/$order");

        }
        else{
            return $this->json([
                "error"=> "no hay nada en el carrito"
            ]);
        }
    }
    #[Route('/order/{order}')]
    public function chargeOrder(Request $request,$order,
                                EntityManagerInterface $entityManager,
                                LoggerInterface        $logger): Response
    {

        $connection = $entityManager->getConnection();
        $chargeOrder=$connection->fetchAllAssociative("
        Select * from `order` 
         where id= $order");

        return $this->render('order/chargeOrder.html.twig',[
            "order" => $order


        ]);



    }
    #[Route('/getOrder/{order}')]
    public function getOrder(Request $request,$order,
                                 EntityManagerInterface $entityManager,
                                 LoggerInterface    $logger)
    {
        $userId = $this->getUser()->getId();

        $connection = $entityManager->getConnection();

        $product = $connection->fetchAllAssociative("
            SELECT *
            FROM carrito 
            join product on product.id=carrito.id_product
            join `order`  on order.id= carrito.order_id
            join status on status.id = order.status_id
             where carrito.user_id = $userId and order_id= $order"
        );
        $status =$connection->fetchAssociative(" SELECT *
            FROM status 
            
            join `order`  on order.status_id= status.id
             where   order.id= $order");


        return $this->json([

            "productos" => $product,
            "status"=> $status
        ]);
    }
    #[Route('/allStatus')]
    public function allStatus(Request $request,
                                EntityManagerInterface $entityManager,
                                LoggerInterface        $logger): Response
    {

        $connection = $entityManager->getConnection();
        $status=$connection->fetchAllAssociative("
        Select * from status 
        "
         );
        return $this->json([
            "status"=> $status
        ]);

    }
    #[Route('/cancelOrder/{order}')]
    public function cancelOrder(Request $request, $order,
                              EntityManagerInterface $entityManager,
                              LoggerInterface        $logger): Response
    {

        $connection = $entityManager->getConnection();
        $status=$connection->executeQuery("
        Update `order` set status_id = 60
                        where   order.id= $order
        "
        );

        return $this->json([


            "status"=> "cancelado"
        ]);



    }




}