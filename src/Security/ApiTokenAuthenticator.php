<?php

namespace App\Security;

use App\Repository\ApiTokenRepository;
use http\Env\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var ApiTokenRepository
     */
    private $apiTokenRepository;

    public function __construct(ApiTokenRepository $apiTokenRepository)
    {
        $this->apiTokenRepository = $apiTokenRepository;
    }

    public function supports(Request $request)
    {
        return $request->headers->has('Authorization')
            && 0 == strpos($request->headers->get('Authorization'), 'Bearer ');
    }

    public function getCredentials(Request $request)
    {
        $authHeader = $request->headers->get('Authorization');

        // skip beyond "Bearer "
        return substr($authHeader, 7);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = $this->apiTokenRepository->findOneBy(['token' => $credentials]);

        if(!$token){
            //@TODO исправь на нормальный 401
            return null;
        }

        return $token->getUser();
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        dd('Good job');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // todo
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // todo
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // todo
    }

    public function supportsRememberMe()
    {
        // todo
    }
}
