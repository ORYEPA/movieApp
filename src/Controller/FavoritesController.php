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

class FavoritesController extends AbstractController
{

    #[Route('/favorites')]
    public function userbookmark(Request $request): Response
    {

        return $this->render('user/favorites.html.twig',[
        ]);
    }
    #[Route('/Cfavorites')]
    public function chargefav(Request $request, EntityManagerInterface $entityManager,
                              LoggerInterface $logger): Response
    {
        $connection = $entityManager->getConnection();
        $userid=1;
        $comments = $connection->fetchAllAssociative("
            SELECT movie_id
            FROM user_favorites 
            WHERE user_id = $userid"
        );


        if(!$comments) {
            $action = "No Tiene favoritos";


        }else{
            $action = "tiene favoritos";

        }
        return $this->json([
            "Tiene comentarios" => $action,

            "comments" => $comments
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
