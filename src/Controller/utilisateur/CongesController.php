<?php

namespace App\Controller\utilisateur;

use App\Entity\Commentaire;
use App\Entity\Conges;
use App\Entity\Label;
use App\Form\CommentaireUserType;
use App\Form\CongesType;
use App\Repository\CongesRepository;
use App\Repository\LabelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/')]
#[IsGranted('ROLE_USER')]
class CongesController extends AbstractController
{
    #[Route('/', name: 'app_conges_index', methods: ['GET'])]
    public function index(CongesRepository $congesRepository, LabelRepository $labelRepository, Security $security): Response
    {
        $user = $this->getUser();

        // Récupération des congés selon le rôle de l'utilisateur
        if ($security->isGranted('ROLE_ADMIN')) {
            $conges = $congesRepository->findAll();
            $labels = $labelRepository->findAll(); // Charger tous les labels
        } else {
            $conges = $congesRepository->findBy(['user' => $user]);
            $labels = []; // Aucun label à afficher pour les utilisateurs normaux
        }
    
        return $this->render('conges/index.html.twig', [
            'conges' => $conges,
            'labels' => $labels,
        ]);
    }

    #[Route('/new', name: 'app_conges_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $conge = new Conges();
        $conge->setUser($user);

        // Assigner le label par défaut (id = 1)
        $label = $entityManager->getRepository(Label::class)->find(1);
        if ($label) {
            $conge->setLabel($label);
        }

        $form = $this->createForm(CongesType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($conge);
            $entityManager->flush();

            return $this->redirectToRoute('app_conges_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conges/new.html.twig', [
            'conge' => $conge,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_conges_show', methods: ['GET'])]
    public function show(Conges $conge): Response
    {
        // Vérifier que l'utilisateur a le droit de voir ce congé
        // $this->denyAccessUnlessGranted('VIEW', $conge);

        return $this->render('conges/show.html.twig', [
            'conge' => $conge,
        ]);
    }

    #[Route('/{id}', name: 'app_conges_delete', methods: ['POST'])]
    public function delete(Request $request, Conges $conge, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur a le droit de supprimer ce congé
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete' . $conge->getId(), $request->request->get('_token'))) {
            $entityManager->remove($conge);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_conges_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/update-label', name: 'app_conges_update_label', methods: ['POST'])]
public function updateLabel(Request $request, Conges $conge, EntityManagerInterface $entityManager): Response
{
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $labelId = $request->request->get('label');
    $label = $entityManager->getRepository(Label::class)->find($labelId);

    if ($label) {
        $conge->setLabel($label);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_conges_index');
}

#[Route('/conges/{id}/add-comment', name: 'app_conges_add_comment', methods: ['POST'])]
public function addComment(Request $request, Conges $conge, EntityManagerInterface $entityManager): Response
{
    $commentaire = new Commentaire();
    $commentaire->setConge($conge);
    $commentaire->setReponseUser($request->request->get('reponseUser'));

    $entityManager->persist($commentaire);
    $entityManager->flush();

    return $this->redirectToRoute('conges/show.html.twig', [
    'conge' => $conge // l'objet conge doit être bien défini avec un id
    ]);
}


#[Route('/{id}/edit', name: 'app_conges_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Conges $conge, EntityManagerInterface $entityManager): Response
{
    // Création du formulaire pour éditer le congé
    $form = $this->createForm(CongesType::class, $conge);
    $form->handleRequest($request);

    // Création du formulaire pour ajouter un commentaire
    $commentaire = new Commentaire();
    $commentaire->setConge($conge); // Associe le commentaire au congé
    $commentaireForm = $this->createForm(CommentaireUserType::class, $commentaire);
    $commentaireForm->handleRequest($request);

    // Gestion de l'envoi du formulaire de commentaire
    if ($commentaireForm->isSubmitted() && $commentaireForm->isValid()) {
        $entityManager->persist($commentaire);
        $entityManager->flush();

        return $this->redirectToRoute('app_conges_edit', ['id' => $conge->getId()]);
    }

    // Gestion de l'envoi du formulaire de congé
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_conges_index', [], Response::HTTP_SEE_OTHER);
    }

    // Récupération des commentaires associés au congé
    $commentaires = $entityManager->getRepository(Commentaire::class)->findBy(['conge' => $conge]);

    return $this->render('conges/edit.html.twig', [
        'conge' => $conge,
        'form' => $form->createView(),
        'commentaireForm' => $commentaireForm->createView(),
        'commentaires' => $commentaires, // Passe les commentaires au template
    ]);
}
}
