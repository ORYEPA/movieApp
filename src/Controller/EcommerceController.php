<?php


namespace App\Controller;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[Route('/app')]
class EcommerceController extends AbstractController
{

    #[Route('/chargeproducts')]
    public function chargeProducts(Request $request,
                                     EntityManagerInterface $entityManager,
                                     LoggerInterface        $logger): Response
    {

        $connection = $entityManager->getConnection();
        $products = $connection->fetchAllAssociative("
            SELECT *
            FROM product "
        );



        return $this->json([

            "productos" => $products

        ]);

    }
    #[Route('/getProduct')]
    public function getProduct(Request $request,
                                     EntityManagerInterface $entityManager,
                                     LoggerInterface        $logger): Response
    {
        $idProduct= $request->get("idProduct",);
        $connection = $entityManager->getConnection();
        $product = $connection->fetchAllAssociative("
            SELECT *
            FROM product where id = $idProduct"
        );



        return $this->json([

            "productos" => $product
        ]);

    }
    #[Route('/addCart')]
    public function addCart(Request $request,
                               EntityManagerInterface $entityManager,
                               LoggerInterface        $logger): Response
    {
        $userId = $this->getUser()->getId();
        $quantity=1;
        $idProduct= $request->get("idProduct",);
        $connection = $entityManager->getConnection();
        $exist= $connection->executeQuery("
        Select  * from carrito where id_product= '$idProduct'");


        $si= $exist->fetchAssociative();
        $action= "no se guardo";

        if(!$si){
            $product = $connection->executeQuery("
            Insert into carrito (id_product,quantity,user_id) 
            values ($idProduct, $quantity, $userId)"
            );

            $action= "se guardo";
        }
        else{
            $prod = $connection->executeQuery("
            update carrito set quantity = quantity +1 where id_product=$idProduct"
            );
        }







        return $this->json([

            "productos" => "se guardo",
            "action"=> $action

        ]);

    }
    #[Route('/getCarrito')]
    public function getCarrito(Request $request,
                               EntityManagerInterface $entityManager,
                               LoggerInterface        $logger): Response
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
             where user_id = $userId"
        );



        return $this->json([

            "productos" => $product,
            "total"=> $total
        ]);

    }
    #[Route('/updateQuantity')]
    public function updateQuantity(Request $request,
                               EntityManagerInterface $entityManager,
                               LoggerInterface        $logger): Response
    {
        $userId = $this->getUser()->getId();
        $idProduct= $request->get("idProduct",);
        $action= $request->get("action",);
        $connection = $entityManager->getConnection();


        if ($action == "suma"){
            $prod = $connection->executeQuery("
            update carrito set quantity = quantity +1 where id_product=$idProduct"
            );


        }
        elseif ($action == "resta"){
            $prod = $connection->executeQuery("
            select  quantity -1 from carrito where id_product=$idProduct"
            );
            $com= $prod->fetchOne();
            if ($com <= 0 ){
                $prod = $connection->executeQuery("
            delete from carrito  where id_product=$idProduct"
                );
            }
            else {
                $prod = $connection->executeQuery("
            update carrito set quantity = quantity -1 where id_product=$idProduct"
                );

            }


        }




        return $this->json([

            "productos" => "se actualizo "
        ]);

    }


}
