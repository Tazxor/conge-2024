<?php
namespace App\Controller;

use App\Entity\Label;
use App\Repository\LabelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LabelController extends AbstractController
{
    #[Route('/api/label', name: 'app_label_index', methods: ['GET'])]
    public function index(LabelRepository $labelRepository, SerializerInterface $serializer): JsonResponse
    {
        $labels = $labelRepository->findAll();
        $jsonLabels = $serializer->serialize($labels, 'json', ['groups' => 'getLabel']);
        return new JsonResponse($jsonLabels, Response::HTTP_OK, [], true);
    }

    #[Route('/api/label/{id}', name: 'app_label_show', methods: ['GET'])]
    public function show(Label $label, SerializerInterface $serializer): JsonResponse
    {
        $jsonLabel = $serializer->serialize($label, 'json', ['groups' => 'getLabel']);
        return new JsonResponse($jsonLabel, Response::HTTP_OK, [], true);
    }

    #[Route('/api/label', name: 'app_label_create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')] // Seules les personnes avec ROLE_ADMIN peuvent créer des labels
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $label = $serializer->deserialize($request->getContent(), Label::class, 'json');
        $em->persist($label);
        $em->flush();

        $jsonLabel = $serializer->serialize($label, 'json', ['groups' => 'getLabel']);
        return new JsonResponse($jsonLabel, Response::HTTP_CREATED, ['Location' => $this->generateUrl('app_label_show', ['id' => $label->getId()])], true);
    }

    #[Route('/api/label/{id}', name: 'app_label_edit', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')] // Seules les personnes avec ROLE_ADMIN peuvent modifier des labels
    public function edit(Request $request, Label $label, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $updatedLabel = $serializer->deserialize($request->getContent(), Label::class, 'json');
        $label->setStatus($updatedLabel->getStatus());
        $em->flush();

        $jsonLabel = $serializer->serialize($label, 'json', ['groups' => 'getLabel']);
        return new JsonResponse($jsonLabel, Response::HTTP_OK, [], true);
    }

    #[Route('/api/label/{id}', name: 'app_label_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')] // Seules les personnes avec ROLE_ADMIN peuvent supprimer des labels
    public function delete(Label $label, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($label);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
