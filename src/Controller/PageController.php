<?php


namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/login')]
    public function login(Request $request): Response
    {
        return $this->render('user/login.html.twig',[

        ]);
    }


    #[Route('/homepage')]
    public function home(Request $request): Response
    {

        return $this->render('user/base.html.twig',[

        ]);
    }


    #[Route('/movie/{movieId}')]
    public function movieInfo(Request $request, $movieId): Response
    {

        return $this->render('user/movieinfo.html.twig',[
            "movieId" => $movieId
        ]);
    }


    #[Route('/checkLogin')]
    public function checkLogin(Request $request, EntityManagerInterface $entityManager): Response
    {
        $email = $request->get("email", null);
        $password = $request->get("password", null);

        $user = $entityManager->getConnection()->fetchOne("
            SELECT password
            FROM user
            WHERE email = :email
            LIMIT 1
        ", [
            "email" => $email
        ]);

        if($user["password"] == $password) {
            return $this->render('user/homepage.html.twig',[

            ]);
        }

        return $this->render('user/login.html.twig',[
            "error" => "Usuario no existe"
        ]);

    }
}
