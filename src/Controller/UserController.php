<?php


namespace App\Controller;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Doctrine\DBAL\DriverManager;

class UserController extends AbstractController
{




    #[Route('/AccessUserInfo')]
    public function userinfo(Request $request, EntityManagerInterface $entityManager,
                             LoggerInterface $logger): Response
    {
        $connection = $entityManager->getConnection();
        $idu=14;

        $userdb = $connection->executeQuery("
            SELECT email ,username
            FROM user 
            WHERE id=$idu ");
        $userExist = $userdb->fetchAssociative()   ;
        return $this->json([


            "info" => $userExist,

        ]);
    }





    /**
     * @throws Exception
     */
    #[Route('/checkLogin')]
    public function checkLogin(Request $request, EntityManagerInterface $entityManager,
                               LoggerInterface $logger): Response
    {
        $email = $request->get("email", null);
        $password = $request->get("password", null);

        $connection = $entityManager->getConnection();

        $userdb = $connection->executeQuery("
            SELECT  password, email 
            FROM user 
            WHERE email = '$email' AND password= '$password' ");

        $userExist = $userdb->fetchAssociative()   ;


        if($userExist) {
            $msg = "Ya existe usuario con el mismo correo o el mismo username";
            return $this->redirectToRoute('homepage', [
                "msg" => $msg
            ]);
        }

        return $this->redirectToRoute('login', [
            "msg" => "no existe, vete al register"

        ]);

    }

    #[Route('/checkregister')]
    public function checkregister(Request $request, EntityManagerInterface $entityManager,
                                  LoggerInterface $logger): Response
    {
        $email = $request->get("email", null);
        $username = $request->get("username", null);
        $password = $request->get("password", null);

        $connection = $entityManager->getConnection();


        //Primero, revisamos si existr algunn usuario con el mismo username o el mismo email

        $userdb = $connection->executeQuery("
            SELECT  password, email 
            FROM user 
            WHERE email = '$email' OR username = '$username' ");

        $userExist = $userdb->fetchAssociative()   ;

        if($userExist) {
            $msg = "Ya existe usuario con el mismo correo o el mismo username";
            return $this->redirectToRoute('register', [
                "msg" => $msg
            ]);
        }

        $query =  $connection->executeQuery("INSERT INTO user (email, password, username) values('$email','$password','$username')");




        $msg = "Se ha registrado el usuario";
        return $this->redirectToRoute('login', [
            "msg" => $msg

        ]);

    }

}
