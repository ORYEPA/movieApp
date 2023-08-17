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

class PageController extends AbstractController
{
    #[Route('/')]
    public function index(Request $request)
    {
        return $this->render('index.html.twig',[

        ]);
    }

    #[Route('/login', name:"login")]
    public function login(Request $request): Response
    {
        return $this->render('user/login.html.twig',[

        ]);
    }
    #[Route('/register', name: "register")]
    public function register(Request $request): Response
    {






        return $this->render('user/register.html.twig',[
            "error" => false
        ]);
    }


    #[Route('/homepage', name: "homepage")]
    public function home(Request $request): Response
    {


        return $this->render('user/homepage.html.twig',[

        ]);
    }
    #[Route('/userSettings')]
    public function userinfo(Request $request): Response
    {

        return $this->render('user/userinfo.html.twig',[

        ]);
    }
    #[Route('/favorites/{movieId}')]
    public function userbookmark(Request $request, $movieId): Response
    {

        return $this->render('user/favorites.html.twig',[
            "movieId" => $movieId

        ]);
    }


    #[Route('/movie/{movieId}')]
    public function movieInfo(Request $request, $movieId): Response
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




        $msg = "Se ha registrado el ysuario";
        return $this->redirectToRoute('login', [
            "msg" => $msg

        ]);

    }
    #[Route('/checkfavorite')]
    public function checkfavorite(Request $request, EntityManagerInterface $entityManager,
                               LoggerInterface $logger): Response
    {
        $movie_id = $request->get("movieid", null);
        $action = $request->get("action", null);
        $userId = 1; // TODO: en el futuro tendra que salir de la "session"


        $connection = $entityManager->getConnection();

        // if SELECT **+** dsadsa
        $existMovie = $connection->fetchOne("
            SELECT movie_id 
            FROM user_favorites 
            WHERE movie_id = $movie_id AND 
                  user_id = $userId");

        $logger->debug("EXISTE zz:".json_encode($existMovie));

        if(!$existMovie) {
            $action = "Guardado";
            $connection->executeQuery("
                INSERT INTO user_favorites (user_id, movie_id) 
                values($userId, '$movie_id')");
        } else{
            $action = "Borrado";
            $connection->executeQuery("
                DELETE 
                FROM user_favorites
                WHERE movie_id = $movie_id 
                  AND user_id = $userId 
                ");
        }

        return $this->json([
            "guardao??" => $action,
            "mov" => $existMovie
        ]);

    }


}
