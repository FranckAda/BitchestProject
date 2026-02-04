<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Enum\Roles;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/admin', name: 'api_admin_users')]
final class UserController extends AbstractController
{

  #[Route('', name: '_list', methods: ['GET'])]
  public function list(UserRepository $userRepository): JsonResponse
  {
    $users = $userRepository->findAll();
    if ($users) {
      return $this->json($users);
    } else {
      return $this->json(['message' => 'No users found'], 404);
    }
  }


  #[Route('/new', name: '_create', methods: ['POST'])]
  public function create(EntityManagerInterface $entityManager, Request $request): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
      return $this->json(['error' => 'Invalid JSON: ' . json_last_error_msg()], 400);
    }
    $user = new User();
    $user->setCreationDate(new \DateTime());

    $form = $this->createForm(UserType::class, $user);
    $form->submit($data);

    if (!$form->isValid()) {
      return $this->json([
        'errors' => (string) $form->getErrors(true),
      ], 422);
    }


    $entityManager->persist($user);
    $entityManager->flush();

    return $this->json([
      'message' => 'User created',
      'id' => $user->getId(),
    ]);
  }



  #[Route('/{id<\d+>}', name: '_show', methods: ['GET'])]
  public function show(User $user): JsonResponse
  {
    return $this->json($user);
  }


  #[Route('/{id<\d+>}/edit', name: '_update', methods: ['PUT', 'PATCH'])]
  public function update(
    Request $request,
    EntityManagerInterface $entityManager,
    User $user
  ): JsonResponse {
    $data = json_decode($request->getContent(), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
      return $this->json(['error' => 'Invalid JSON: ' . json_last_error_msg()], 400);
    }

    $form = $this->createForm(UserType::class, $user);

    // PATCH: update partiel (ne met pas à null les champs absents)
    $clearMissing = $request->getMethod() !== 'PATCH';
    $form->submit($data, $clearMissing);

    if (!$form->isValid()) {
      return $this->json([
        'errors' => (string) $form->getErrors(true, false),
      ], 422);
    }

    $entityManager->flush();

    return $this->json($user);
  }

  #[Route('/{id<\d+>}/delete', name: '_delete', methods: ['GET'])]
  public function delete(EntityManagerInterface $entityManager, User $user): JsonResponse
  {
    $entityManager->remove($user);
    $entityManager->flush();

    return $this->json(['message' => 'User deleted successfully'], 200);
  }
}
