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


class ListController extends AbstractController
{


    #[Route('/list/{listid}')]
    public function userbookmark(Request $request, $listid, EntityManagerInterface $entityManager,
                                 LoggerInterface $logger): Response
    {

        return $this->render('user/listinfo.html.twig',[
            "listid" => $listid
        ]);
    }

    #[Route('/addlist')]
    public function listname(Request $request,
                             EntityManagerInterface $entityManager,
                             LoggerInterface $logger): Response
    {
        $connection = $entityManager->getConnection();
        $nameli = $request->get("nameli", null);
        $userId = 14;

        if((strlen(trim($nameli))) == 0 || is_null($nameli)) {
            return $this->json([
                "code" => Response::HTTP_BAD_REQUEST,
                "msg" => "No se recibió nombre de la lista"
            ]);
        }

        $query = $connection->executeQuery("INSERT INTO 
            listname ( nameli, userid) 
            values('$nameli','$userId')");

        return $this->json(["valor" => "ok"]);
    }

    #[Route('/addmovielist')]
    public function addmtol(Request $request,
                                EntityManagerInterface $entityManager,
                                LoggerInterface $logger): Response
    {
        $connection = $entityManager->getConnection();
        $movie_id = $request->get("movieid", null);
        $list_id = $request->get("list_id", null);
        $val = $request->get("val", null);

        $existMovie = $connection->fetchAllAssociative("
            SELECT movie_id 
            FROM list_movie 
            WHERE movie_id = $movie_id AND 
                  list_id=$list_id");
        $logger->debug("EXISTE zz:".json_encode($existMovie));

        if(!$existMovie || $val== 1) {
            $action = "Guardado";
            $query = $connection->executeQuery("INSERT INTO 
            list_movie (list_id, movie_id) 
            values('$list_id','$movie_id')");
        }
        else{
            $action = "ya esta en lista";
            $val= 1;

        }

        return $this->json([
            "esta" => $action,
            "valor" => $val
        ]);

    }


    #[Route('/chargelist')]
    public function getlists(Request $request,
                                     EntityManagerInterface $entityManager,
                                     LoggerInterface        $logger): Response
    {

        $id = 14;
        $connection = $entityManager->getConnection();
        $list = $connection->fetchAllAssociative("
            SELECT nameli,listid
            FROM listname 
                
            WHERE userid = $id"
        );
        if (!$list) {
            $action = "No Tiene listas";
        } else {
            $action = "tiene listas";

        }
        return $this->json([
            "code" => Response::HTTP_OK,
            "msg" => $action,
            "list" => $list
        ]);

    }
    #[Route('/chargelistinfo')]
    public function getmovieslist(Request $request,
                                     EntityManagerInterface $entityManager,
                                     LoggerInterface        $logger): Response
    {
        $list_id = $request->get("list_id", null);


        $connection = $entityManager->getConnection();
        $movies = $connection->fetchAllAssociative("
            SELECT movie_id
            FROM list_movie
            WHERE list_id=$list_id
                "
        );
        if (!$movies) {
            $action = "No Tiene peliculas";
        } else {
            $action = "tiene peliculas";

        }
        return $this->json([
            "code" => Response::HTTP_OK,
            "msg" => $action,
            "list" => $movies
        ]);

    }





}
