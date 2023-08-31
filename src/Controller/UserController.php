<?php


namespace App\Controller;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Doctrine\DBAL\DriverManager;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[Route('/app')]
class UserController extends AbstractController
{




    #[Route('/AccessUserInfo')]
    public function userinfo(Request $request, EntityManagerInterface $entityManager,
                             LoggerInterface $logger): Response
    {
        $connection = $entityManager->getConnection();
        $id = $this->getUser()->getId();

        $userdb = $connection->executeQuery("
            SELECT email ,username
            FROM user 
            WHERE id=$id ");
        $userExist = $userdb->fetchAssociative()   ;
        return $this->json([


            "info" => $userExist,

        ]);
    }
    #[Route('/changepssword')]
    public function changepss(Request $request, EntityManagerInterface $entityManager,
                             LoggerInterface $logger,
                              UserPasswordHasherInterface $passwordHasher): Response
    {
        $connection = $entityManager->getConnection();
        $oldpss = $request->get("oldpss", null);
        $newpass = $request->get("newpass", null);

        $id = $this->getUser()->getId();
        $user = $this->getUser();

        $password = $connection->fetchOne("
            SELECT password
            FROM user 
            WHERE id=$id ");

        $hashedPassword = $passwordHasher->isPasswordValid($user, $oldpss);

        if($hashedPassword){


            $hashnewpass = $passwordHasher->hashPassword(
                $user,
                $newpass
            );


            $respuestaa =  $connection->executeQuery(
                "UPDATE user set password = :password WHERE user.id = :user_id ", [
                "password" => $hashnewpass,
                "user_id" => $id
            ]);

            return $this->json([
                "info" => "Tu contraseña se actualizo",
                "con"=> "",
                "res"=>$hashnewpass
            ]);
       }else{
            return $this->json([
                "info" => "Contraseña incorrecta",


            ]);

        }

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



}
