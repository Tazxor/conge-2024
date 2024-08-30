<?php

namespace App\Controller\utilisateur;

use App\Entity\Commentaire;
use App\Entity\Conges;
use App\Form\CommentaireUserType;
use App\Form\CommentaireAdminType;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commentaire')]
#[IsGranted('ROLE_USER')]
class CommentaireController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository): Response
    {


        // Affiche tous les commentaires
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireUserType::class, $commentaire);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_commentaire_index');
        }
    
        return $this->render('conges/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        // Affiche un commentaire spécifique
        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commentaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        // Vérifie le rôle de l'utilisateur pour restreindre l'accès à l'édition
        if ($this->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(CommentaireAdminType::class, $commentaire);
        } elseif ($this->getUser() === $commentaire->getConge()->getUser()) {
            $form = $this->createForm(CommentaireUserType::class, $commentaire);
        } else {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier ce commentaire.');
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_index', ['id' => $commentaire->getConge()->getId()]);
        }

        return $this->render('commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si l'utilisateur peut supprimer le commentaire
        if ($this->isGranted('ROLE_ADMIN') || $this->getUser() === $commentaire->getConge()->getUser()) {
            if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
                $entityManager->remove($commentaire);
                $entityManager->flush();
            }
        } else {
            throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer ce commentaire.');
        }

        return $this->redirectToRoute('app_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }


    


    #[Route('/conges/{id}/addcomment', name: 'app_conges_add_comment', methods: ['GET', 'POST'])]
    public function addComment(Request $request, Conges $conges, EntityManagerInterface $entityManager): Response
    {
        // Récupère le commentaire existant pour le congé ou crée un nouveau
        $commentaire = $entityManager->getRepository(Commentaire::class)->findOneBy(['conge' => $conges]);

        if (!$commentaire) {
            $commentaire = new Commentaire();
            $commentaire->setConge($conges);
        }

        // Vérifie le rôle de l'utilisateur connecté et charge le bon formulaire
        if ($this->isGranted('ROLE_ADMIN')) {
            $form = $this->createForm(CommentaireAdminType::class, $commentaire);
        } else {
            $form = $this->createForm(CommentaireUserType::class, $commentaire);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_index', ['id' => $conges->getId()]);
        }

        return $this->render('commentaire/_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
