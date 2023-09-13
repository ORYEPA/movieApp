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

#[Route('/app')]
class PageController extends AbstractController
{


    #[Route('/oldlogin', name:"login")]
    public function login(Request $request): Response
    {
        return $this->render('user/login.html.twig',[

        ]);
    }

    


    #[Route('/homepage', name: "homepage")]
    public function home(Request $request): Response
    {


        return $this->render('user/homepage.html.twig',[

        ]);
    }
    #[Route('/responsive', name: "resp      onsive")]
    public function responseex(Request $request): Response
    {


        return $this->render('user/responsive.html.twig',[

        ]);
    }
    #[Route('/pruebas', name: "dasd")]
    public function pruebas(Request $request): Response
    {


        return $this->render('user/pruebas.html.twig',[

        ]);
    }
    #[Route('/ecommerce', name: "ecommerce")]
    public function ecommerce(Request $request): Response
    {


        return $this->render('ecommerce/landing.html.twig',[

        ]);
    }
    #[Route('/userSettings', name:"userSettings")]
    public function userinfo(Request $request): Response
    {

        return $this->render('user/userinfo.html.twig',[

        ]);
    }
    #[Route('/Usercomments', name:"usercomments")]
    public function usercomments(Request $request): Response
    {

        return $this->render('user/Usercomments.html.twig',[

        ]);
    }
    #[Route('/UserLists', name:"userlist")]
    public function userlists(Request $request): Response
    {

        return $this->render('user/Userlists.html.twig',[

        ]);
    }


    #[Route('/movie/{movieId}',name:"moviesinfo")]
    public function movieInfo(Request $request, $movieId, EntityManagerInterface $entityManager,
                              LoggerInterface $logger): Response
    {


        return $this->render('user/movieinfo.html.twig',[
            "movieId" => $movieId


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
