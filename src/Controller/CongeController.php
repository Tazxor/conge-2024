<?php

namespace App\Controller;

use App\Entity\Conges;
use App\Repository\CongesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class CongeController extends AbstractController
{
    // Voir tous les congés (accès limité aux admins)
    #[Route('/api/conges', name: 'conges')]
    #[IsGranted('ROLE_ADMIN')]
    public function getAllConges(CongesRepository $CongeRepository, SerializerInterface $serializer): JsonResponse
    {
        $congeList = $CongeRepository->findAll();
        $jsonCongeList = $serializer->serialize($congeList, 'json', ['groups' => 'getConge']);
        return new JsonResponse($jsonCongeList, Response::HTTP_OK, [], true);
    }
    
    // Créer un congé (accès limité aux utilisateurs authentifiés)
    #[Route('api/conges/create', name: 'createConge', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function createConge(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse 
    {
        $conge = $serializer->deserialize($request->getContent(), Conges::class, 'json');
        $em->persist($conge);
        $em->flush();

        $jsonConge = $serializer->serialize($conge, 'json', ['groups' => 'getConge']);
        
        $location = $urlGenerator->generate('congeShow', ['id' => $conge->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonConge, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    // Trouver un congé (accès limité aux utilisateurs authentifiés)
    #[Route('api/conge/{id}', name: 'congeShow', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function congeShow(Conges $conge, SerializerInterface $serializer): JsonResponse
    {
        $jsonConge = $serializer->serialize($conge, 'json', ['groups' => 'getConge']);
        return new JsonResponse($jsonConge, Response::HTTP_OK, [], true);
    }

    // Modifier un congé (accès limité aux admins)
    #[Route('api/conges/{id}', name:'updateConge', methods:['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function updateConge(Request $request, SerializerInterface $serializer, Conges $currentConge, EntityManagerInterface $em): JsonResponse 
    {
        $updatedConge = $serializer->deserialize($request->getContent(), Conges::class, 'json');

        $em->persist($updatedConge);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    // Supprimer un congé (accès limité aux admins)
    #[Route('api/conges/{id}', name: 'deleteConge', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteConge(Conges $conge, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($conge);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
