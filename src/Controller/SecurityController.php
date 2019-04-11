<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\LoginFormAuthenticatorAuthenticator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends BaseController
{
    /**
     * @Route("/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUserName = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'error' => $error,
            'lastUserName' => $lastUserName
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('Will be intercepted before getting here');
    }

    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @param GuardAuthenticatorHandler $authenticatorHandler
     * @param LoginFormAuthenticatorAuthenticator $authenticator
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $encoder,
        GuardAuthenticatorHandler $authenticatorHandler,
        LoginFormAuthenticatorAuthenticator $authenticator)
    {
        if($request->isMethod('POST')){
            $user = new User();
            $user->setEmail($request->request->get('email'));
            $user->setFirstName('Max');
            $user->setPassword($encoder->encodePassword(
                $user,
                $request->request->get('password')
            ));

            $em->persist($user);
            $em->flush();

            return $authenticatorHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main'
            );
        }

        return $this->render('security/register.html.twig');
    }
}
