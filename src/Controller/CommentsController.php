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

class CommentsController extends AbstractController
{


    #[Route('/movie/{movieId}/Checkcomments')]
    public function comments(Request         $request, EntityManagerInterface $entityManager,
                             LoggerInterface $logger): Response
    {


        $connection = $entityManager->getConnection();

        $comments = $request->get("comment", null);
        $userId = 14;
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
        $comments = $connection->fetchAllAssociative("
            SELECT comments, u.username, u.email ,datem
            FROM comments 
                JOIN dbprueba.user u on comments.user_id = u.id
            WHERE movie_id = $movie_id"
        );
        if (!$comments) {
            $action = "No Tiene comentarios";


        } else {
            $action = "tiene comentarios";

        }
        return $this->json([
            "Tiene comentarios" => $action,

            "comments" => $comments,
            "pelicula" => $movie_id
        ]);

    }
    #[Route('/chargeComments')]
    public function getcommentsbuser(Request $request,
                                EntityManagerInterface $entityManager,
                                LoggerInterface        $logger): Response
    {

        $id = 14;
        $connection = $entityManager->getConnection();
        $comments = $connection->fetchAllAssociative("
            SELECT comments, movie_id,datem
            FROM comments 
                
            WHERE user_id = $id"
        );
        if (!$comments) {
            $action = "No Tiene comentarios";


        } else {
            $action = "tiene comentarios";

        }
        return $this->json([
            "Tiene comentarios" => $action,

            "comments" => $comments,

        ]);

    }

    #[Route('/movie/{movieId}/deleteComments')]
    public function deletecomments(Request                $request, $movieId,
                                   EntityManagerInterface $entityManager,
                                   LoggerInterface        $logger): Response
    {

        $movie_id = $request->get("movieId", null);
        $comments = $request->get("comment", null);

        $userId = 14;
        $connection = $entityManager->getConnection();
        $connection->executeQuery("
                DELETE 
                FROM comments
                WHERE movie_id = $movie_id 
                  AND user_id = $userId
                ");
        $action = "si se borro, creo";

        return $this->json([
            "se borro?" => $action,


        ]);


    }



}
