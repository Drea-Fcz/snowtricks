<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Event\EditTrickEvent;
use App\Form\TrickFormType;
use App\Repository\TrickRepository;
use App\Service\TrickMediaService;
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
                                 private EntityManagerInterface $_em,
                                 private TrickRepository $repository,
                                 private TrickMediaService $mediaService
    )
    {
    }

    #[Route('', name: '_trick.list')]
    public function index(): Response
    {

        return $this->render('trick/index.html.twig', [
            'tricks' => $this->repository->findAll()
        ]);
    }



    #[Route('/detail/{slug}/{nbComments}', name: 'trick_show_comments', methods: ['GET'])]
    public function showWithComments(Trick $trick, int $nbComments): Response
    {

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'nbComments' => $nbComments,
        ]);
    }

    #[Route('/detail/{slug}', name: 'trick_show', methods: ['GET'])]
    public function show(Trick $trick): Response
    {
        return $this->render('trick/show.html.twig', [
            'trick' => $trick
        ]);
    }


    /**
     * @param Trick|null $trick
     * @param Request $request
     * @return Response
     */
    #[
        Route('/edit/{slug}', name: '_trick.edit'),
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
            $trick->setSlug();
            $trick = $this->mediaService->checkAndAddMedia($trick);

            $this->_em->persist($trick);
            $this->_em->flush();


            if ($new) {
                // create new event with AddPersonEvent
                $addPersonEvent = new EditTrickEvent($trick);
                // We need to dispatch the event
                $this->dispatcher->dispatch($addPersonEvent, EditTrickEvent::EDIT_TRICK_EVENT);
            }
            $this->addFlash('success', $message);
            if ($new) {
                return $this->redirectToRoute('_trick.list');
            } else {
                return $this->redirectToRoute('trick_show', ['slug' => $trick->getSlug()]);
            }


        } else {
            return $this->render('trick/edit.html.twig', [
                'trick' => $trick,
                'form' => $form->createView()
            ]);
        }
    }

    #[
        Route('/{id}', name: '_trick_delete', methods: ['POST']),
        isGranted('IS_AUTHENTICATED')
    ]

    public function delete(Trick $trick): Response
    {
        if ($trick->getId() !== null) {
            $this->_em->remove($trick);
            $this->_em->flush();

            $this->addFlash('success', "The trick was deleted");

        } else {

            $this->addFlash('error', "This trick doesn't  exist");
        }

        return  $this->redirectToRoute('_trick.list');
    }


}
