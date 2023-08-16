<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
class PageController extends AbstractController
{

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
    public function movieInfo(Request $request,$movieId): Response

    {

        return $this->render('user/movieinfo.html.twig',[
            "movieId"=>$movieId





        ]);


    }

}
