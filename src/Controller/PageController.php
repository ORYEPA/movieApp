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
    public function movieInfo(Request $request, $movieId, EntityManagerInterface $entityManager,
                              LoggerInterface $logger): Response
    {
        return $this->render('user/movieinfo.html.twig',[
            "movieId" => $movieId


        ]);


    }

    #[Route('/movie/{movieId}/Checkcomments')]
    public function comments(Request $request, EntityManagerInterface $entityManager,
                              LoggerInterface $logger): Response
    {



        $connection = $entityManager->getConnection();

        $comments=$request->get("comment", null);
        $userId = 1;
        $movie_id = $request->get("movieid", null);
        $datem = $request->get("date", null);


        $query =$connection->executeQuery("INSERT INTO 
        comments (movie_id, comments, user_id,datem) 
        values('$movie_id','$comments','$userId', utc_timestamp())");
        return $this->json(["valor"=>"ok"]);

    }

    #[Route('/movie/{movieId}/chargeComments')]
    public function getcomments(Request $request,$movieId,
                                EntityManagerInterface $entityManager,
                             LoggerInterface $logger): Response
    {

        $movie_id = $request->get("movieid", null);
        $connection = $entityManager->getConnection();
        $comments = $connection->fetchAllAssociative("
            SELECT comments, u.username, u.email ,datem
            FROM comments 
                JOIN dbprueba.user u on comments.user_id = u.id
            WHERE movie_id = $movie_id"
                  );
        if(!$comments) {
            $action = "No Tiene comentarios";


        }else{
            $action = "tiene comentarios";

        }
        return $this->json([
            "Tiene comentarios" => $action,

            "comments" => $comments,
            "pelicula"=> $movie_id
        ]);

    }
    #[Route('/movie/{movieId}/deleteComments')]
    public function deletecomments(Request $request,$movieId,
                                EntityManagerInterface $entityManager,
                                LoggerInterface $logger): Response
    {

        $movie_id = $request->get("movieId", null);
        $comments = $request->get("comment", null);

        $userId = 1;
        $connection = $entityManager->getConnection();
        $connection->executeQuery("
                DELETE 
                FROM comments
                WHERE movie_id = $movie_id 
                  AND user_id = $userId
                ");
        $action ="si se borro, creo";

        return $this->json([
            "se borro?" => $action,


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
