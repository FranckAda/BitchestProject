<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/admin', name: 'api_admin')]
final class UserController extends AbstractController
{
  #[Route('/show', name: '_show_user', methods: ['GET'])]
  public function showUser(UserRepository $userRepository): JsonResponse
  {
    $users = $userRepository->findAll();
    if ($users) {
      return $this->json($users, 200, [], ['groups' => 'user:read']);
    } else {
      return $this->json(['message' => 'No users found'], 404);
    }
  }

  #[Route('/new', name: '_new_user', methods: ['GET', 'POST'])]
  public function addNewUser(Request $request, EntityManagerInterface $entityManager): JsonResponse
  {
    $user = new User();
    $data = json_decode($request->getContent(), true);
    if (!$data) {
      return $this->json(['error' => 'Invalid JSON data'], 400);
    }
    $form = $this->createForm(UserType::class, $user);
    $form->submit($data);

    if (!$form->isValid()) {
      return $this->json([
        'errors' => (string) $form->getErrors(true, false),
      ], 422);
    }

    $entityManager->persist($user);
    $entityManager->flush();

    return $this->json([
      'message' => 'User created successfully'
    ], 201);
  }


  #[Route('/{id}', name: '_get_user', methods: ['GET'])]
  public function getUserById(User $user): JsonResponse
  {
    return $this->json(['user' => $user]);
  }

  #[Route('/{id}/edit', name: '_edit_user', methods: ['GET', 'POST'])]
  public function editUserById(int $id): JsonResponse
  {
    return $this->json(["" => "up"]);
  }

  #[Route('/{id}/delete', name: '_delete_user', methods: ['POST'])]
  public function deleteUserById(Request $request, EntityManagerInterface $em, User $user): JsonResponse
  {
    $data = json_decode($request->getContent(), true);
    $token = $data['_token'] ?? '';

    if ($this->isCsrfTokenValid('delete' . $user->getId(), $token)) {
      $em->remove($user);
      $em->flush();
      return $this->json(['message' => 'User deleted successfully']);
    }

    return $this->json(['error' => 'Invalid CSRF token'], 403);
  }
}
