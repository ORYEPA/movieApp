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


    #[Route('/addlist')]
    public function listname(Request $request,
                             EntityManagerInterface $entityManager,
                             LoggerInterface $logger): Response
    {
        $connection = $entityManager->getConnection();
        $nameli = $request->get("nameli", null);
        $userId = 14;

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

        $query = $connection->executeQuery("INSERT INTO 
            list_movie (list_id, movie_id) 
            values('$list_id','$movie_id')");

        return $this->json(["valor" => "jalo"]);

    }
    #[Route('/chargelist')]
    public function getcommentsbuser(Request $request,
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
            "Tiene comentarios" => $action,

            "list" => $list

        ]);

    }





}
