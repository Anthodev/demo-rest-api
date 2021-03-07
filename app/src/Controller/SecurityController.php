<?php

declare(strict_types=1);

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller
 */
#[Route('/api/auth')]
class SecurityController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/login_check', name: 'api_login_check', methods: ['POST'])]
    public function login_check(): Response
    {
        $user = $this->getUser();

        return new Response(
            json_encode(
                [
                    'username' => $user->getUsername(),
                    'roles' => $user->getRoles(),
                ]
            )
        );
    }

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route('/login', name: 'api_login')]
    public function login(
        AuthenticationUtils $authenticationUtils
    ): Response {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return new Response(json_encode(['lastUsername' => $lastUsername, 'error' => $error]));
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(): void
    {
        // controller can be blank: it will never be executed!
        // throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
