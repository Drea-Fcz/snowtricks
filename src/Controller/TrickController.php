<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Event\EditTrickEvent;
use App\Form\TrickFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[
    Route('trick'),
]
class TrickController extends AbstractController
{

    public function __construct( private EventDispatcherInterface $dispatcher,
                                 private EntityManagerInterface $_em
    )
    {
    }

    #[Route('', name: '_trick')]
    public function index(): Response
    {
        return $this->render('trick/index.html.twig', [
            'controller_name' => 'TrickController',
        ]);
    }

    /**
     * @param Trick|null $trick
     * @param Request $request
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     */
    #[
        Route('/edit/{id?0}', name: '_trick.edit'),
        isGranted('IS_AUTHENTICATED')
    ]
    public function edit(Trick $trick = null, Request $request): Response
    {
        $new = false;

        if (!$trick) {
            $new = true;
            $trick = new Trick();
        }

        $form = $this->createForm(TrickFormType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $message = $new ? "You added a new trick with success" : "you edited a trick whit success";
            $trick->setCreatedBy($this->getUser());

            $this->_em->persist($trick);
            $this->_em->flush();

            if ($new) {
                // create new event with AddPersonEvent
                $addPersonEvent = new EditTrickEvent($trick);
                // We need to dispatch the event
                $this->dispatcher->dispatch($addPersonEvent, EditTrickEvent::EDIT_TRICK_EVENT);
            }
            $this->addFlash('Success', $message);

            return $this->redirectToRoute('_trick');
        } else {
            return $this->render('trick/edit.html.twig', [
                'trick' => $trick,
                'form' => $form->createView()
            ]);
        }
    }


}
