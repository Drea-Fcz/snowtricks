<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\TrickRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[
    Route('comment'),
]
class CommentController extends AbstractController
{
    public function __construct(private TrickRepository $repositoryTrick)
    {
    }

    #[  Route('/edit', name: '_comment_edit', methods: ['GET', 'POST']),
        isGranted('IS_AUTHENTICATED')
    ]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $slug = $request->query->get('slug');
        $trick = $this->repositoryTrick->findBy(['slug' => $slug]);
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setTrick($trick[0])
                ->setUser($this->getUser())
                ->setCreatedAt(new DateTime());

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('trick_show', ['slug' => $slug], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form
        ]);
    }
}
