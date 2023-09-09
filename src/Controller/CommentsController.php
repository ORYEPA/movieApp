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
class CommentsController extends AbstractController
{


    #[Route('/movie/{movieId}/Checkcomments')]
    public function comments(Request $request,
                             EntityManagerInterface $entityManager,
                             LoggerInterface $logger): Response
    {
        $connection = $entityManager->getConnection();
        $comments = $request->get("comment", null);
        $userId = $this->getUser()->getId();
        $movie_id = $request->get("movieid", null);
        $datem = $request->get("date", null);

        $query = $connection->executeQuery("INSERT INTO 
            comments (movie_id, comments, user_id,datem) 
            values('$movie_id','$comments','$userId', utc_timestamp())");

        return $this->json(["valor" => "ok"]);
    }

    #[Route('/movie/{movieId}/chargeComments')]
    public function getcomments(Request                $request, $movieId,
                                EntityManagerInterface $entityManager,
                                LoggerInterface        $logger): Response
    {

        $movie_id = $request->get("movieid", null);
        $connection = $entityManager->getConnection();
        $role = $this->getUser()->getRoles();

        $comments = $connection->fetchAllAssociative("
            SELECT comments, u.username, u.email ,datem , comments.id
            FROM comments 
                JOIN dbprueba.user u on comments.user_id = u.id
            WHERE movie_id = $movie_id"
        );
        $role = $this->getUser()->getRoles();

        if(in_array("admin",$role)){
            //si es admin

            $action = "a";
        }
        else{
            //no puedes borrar no eres admin
            $action = "z";
        }
        return $this->json([
            "Rol" => $action,

            "comments" => $comments,
            "pelicula" => $movie_id,
        ]);

    }
    #[Route('/chargeComments')]
    public function getcommentsbuser(Request $request,
                                EntityManagerInterface $entityManager,
                                LoggerInterface        $logger): Response
    {

        $id = $this->getUser()->getId();
        $connection = $entityManager->getConnection();
        $comments = $connection->fetchAllAssociative("
            SELECT comments, movie_id,datem
            FROM comments 
                
            WHERE user_id = $id"
        );



        return $this->json([
            "Rol" => $action,

            "comments" => $comments,

        ]);

    }

    #[Route('/movie/{movieId}/deleteComments')]
    public function deletecomments(Request                $request, $movieId,
                                   EntityManagerInterface $entityManager,
                                   LoggerInterface        $logger): Response
    {

        $movie_id = $request->get("movieId", null);
        $comments = $request->get("comments", null);

        $role = $this->getUser()->getRoles();
        $id = $this->getUser()->getId();

        if(in_array("admin",$role)){
            //si es admin
            $connection = $entityManager->getConnection();
            $connection->executeQuery("
                DELETE 
                FROM comments
                WHERE movie_id = $movie_id 
                  AND user_id = $id 
                And id= $comments
                
                    
                ");
            $action = "si se borro, creo";
        }
        else{
            //no puedes borrar no eres admin
            $action = "no se pudo no eres admin";
        }


        return $this->json([
            "se borro?" => $action,


        ]);


    }



}
