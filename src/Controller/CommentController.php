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
    public function __construct(private TrickRepository $repositoryTrick,
                                private EntityManagerInterface $_em)
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
            'form' => $form,
            'slug' => $slug
        ]);
    }

    #[
        Route('/{id}', name: '_comment_delete', methods: ['POST']),
        isGranted('IS_AUTHENTICATED')
    ]

    public function delete(Comment $comment, Request $request): Response
    {
        $slug = $request->query->get('slug');

        if ($comment->getId() !== null) {
            $this->_em->remove($comment);
            $this->_em->flush();

            $this->addFlash('success', "The comment was deleted");

        } else {

            $this->addFlash('error', "This comment doesn't  exist");
        }

        return $this->redirectToRoute('trick_show', ['slug' => $slug], Response::HTTP_SEE_OTHER);
    }
}
