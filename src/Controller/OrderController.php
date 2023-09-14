<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/order')]
    public function orderIndex(Request $request)
    {

        return $this->render("order/index.html.twig", [

        ]);
    }

}