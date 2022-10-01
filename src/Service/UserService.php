<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class UserService
{
    public function __construct(
        private EntityManagerInterface $_em,
        private UserRepository $repository,
        private MailerService $mailerService,
        private TemplateService $template
    )
    {
    }

    /**
     * @param string $mail
     * @throws TransportExceptionInterface
     */
    public function sendResetPassword(string $mail): void
    {
        $user = $this->repository->findOneBy(array('email' => $mail));

        if ($user !== null) {

            $this->_em->persist($user);
            $this->_em->flush();

            $this->mailerService->sendEmail($mail, 'Reset your password', $this->template->emailResetPasword());
        }
    }

}
