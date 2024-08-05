<?php
namespace App\Controller;

use App\Entity\Commentaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CommentaireController extends AbstractController
{
    #[Route('api/commentaire/create', name: 'commentaire_create', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function createCommentaire(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $commentaire = $serializer->deserialize($request->getContent(), Commentaire::class, 'json');
        $em->persist($commentaire);
        $em->flush();

        $jsonCommentaire = $serializer->serialize($commentaire, 'json', ['groups' => 'getCommentaire']);
        $location = $urlGenerator->generate('commentaire_show', ['id' => $commentaire->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonCommentaire, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('api/commentaire/{id}', name: 'commentaire_show', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function showCommentaire(Commentaire $commentaire, SerializerInterface $serializer): JsonResponse
    {
        $jsonCommentaire = $serializer->serialize($commentaire, 'json', ['groups' => 'getCommentaire']);
        return new JsonResponse($jsonCommentaire, Response::HTTP_OK, [], true);
    }

    #[Route('api/commentaire/edit/{id}', name: 'commentaire_edit', methods: ['PUT'])]
    #[IsGranted('ROLE_USER')]
    public function updateCommentaire(Request $request, SerializerInterface $serializer, Commentaire $commentaire, EntityManagerInterface $em): JsonResponse
    {
        $updatedCommentaire = $serializer->deserialize($request->getContent(), Commentaire::class, 'json');
        $commentaire->setReponseUser($updatedCommentaire->getReponseUser());

        $em->persist($commentaire);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route('api/reponse/admin/{id}', name: 'reponse_admin', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function reponseAdmin(Request $request, SerializerInterface $serializer, Commentaire $commentaire, EntityManagerInterface $em): JsonResponse
    {
        $response = $serializer->deserialize($request->getContent(), Commentaire::class, 'json');
        $commentaire->setReponseAdmin($response->getReponseAdmin());

        $em->persist($commentaire);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
