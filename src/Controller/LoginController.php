<?php

namespace App\Controller;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class LoginController extends AbstractController
{
    #[Route('/')]
    public function loginren(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
    #[Route('/logout', name: 'app_logout', methods: ['POST', 'GET'])]
    public function logout(): never
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
    #[Route('/register', name: "register")]
    public function register(Request $request): Response
    {






        return $this->render('user/register.html.twig',[
            "error" => false
        ]);
    }
    #[Route('/checkregister')]
    public function checkregister(Request $request, EntityManagerInterface $entityManager,
                                  LoggerInterface $logger,UserPasswordHasherInterface $passwordHasher): Response
    {
        $email = $request->get("email", null);
        $username = $request->get("username", null);
        $password = $request->get("password", null);
        $role= "[\"role\"]";
        $connection = $entityManager->getConnection();



        //Primero, revisamos si existr algunn usuario con el mismo username o el mismo email

        $userdb = $connection->executeQuery("
            SELECT  password, email 
            FROM user 
            WHERE email = '$email' OR username = '$username' ");

        $userExist = $userdb->fetchAssociative()   ;

        if($userExist) {
            $msg = "Ya existe usuario con el mismo correo o el mismo username";
            return $this->redirectToRoute('register', [
                "msg" => $msg
            ]);
        }

        $query =  $connection->executeQuery("
            INSERT INTO user (email, password, username,roles) 
            values(:email,'$password','$username','$role')", [
                "email" => $email
        ]);

        // find
        $lastid= $connection ->fetchOne("SELECT LAST_INSERT_ID()");
        $user = $entityManager->getRepository(User::class)->find($lastid);

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $password
        );
        //update

        $query =  $connection->executeQuery(
            "UPDATE user set password = :password WHERE user.id = :user_id ", [
                "password" => $hashedPassword,
                "user_id" => $lastid
        ]);


        $msg = "Se ha registrado el usuario";
        return $this->redirectToRoute('login', [
            "msg" => $msg

        ]);

    }

}
