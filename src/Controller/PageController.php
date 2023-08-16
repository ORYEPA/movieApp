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

    #[Route('/login')]
    public function login(Request $request): Response
    {
        return $this->render('user/login.html.twig',[

        ]);
    }


    #[Route('/homepage', name: "homepage")]
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
        

        $user = $connection->executeQuery("SELECT id, password, email FROM user WHERE email = 'andrea.hernandez@tilatina.com' ");

        $user = $user->fetchAssociative();

        $logger->debug("USUARIO::::::::::::::::::::");
        $logger->debug(json_encode($user));

        if($user) {
            return $this->redirect("homepage");
        }

        return $this->render('user/login.html.twig',[
            "error" => "Usuario no existe"
        ]);

    }


}
