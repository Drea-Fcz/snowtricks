<?php

namespace App\Controller;

use App\Service\UserService;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    public function __construct(private UserService $service)
    {
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/reset-passwordss', name: '_get-reset-passwordss', methods: ['GET'])]
    public function resetPassword(): Response
    {
        return $this->render('security/reset-password.html.twig', [
            'isSent' => false
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/reset-passwordss', name: '_reset-passwordss', methods: ['POST'])]
    public function sendResetPassword(Request $request): Response
    {
        $mail = $request->get('email');
        $this->service->sendResetPassword($mail);

        return $this->render('security/reset-password.html.twig', [
            'isSent' => true
        ]);
    }
}
