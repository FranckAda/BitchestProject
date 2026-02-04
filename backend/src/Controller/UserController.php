<?php

namespace App\Controller;

use App\Entity\User;
<<<<<<< HEAD
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
=======
use App\Enum\Roles;
use App\Form\UserType;
use App\Entity\Wallet;
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

    if (!$users) {
      return $this->json(['message' => 'No users found'], 404);
    }

    $result = array_map(
      fn(User $u) => $this->userToArray($u),
      $users
    );

    return $this->json([
      'users' => $result
    ]);
  }



  #[Route('/new', name: '_create', methods: ['GET', 'POST'])]
  public function create(EntityManagerInterface $em, Request $request): JsonResponse
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
    // ⚠️ Ici, $user a maintenant son role (enum) si ton UserType map bien "role"
    if ($user->getRole() === Roles::CLIENT) {
      $wallet = new Wallet();
      $wallet->setBalance(500);

      // Liaison (le setter de User est ok aussi)
      $user->setWallet($wallet); // => va faire $wallet->setClientId($user)
      // ou directement: $wallet->setClientId($user);

      $em->persist($wallet); // safe (même si cascade persist existe)
    }

    $em->persist($user);
    $em->flush();

    return new JsonResponse([
      'id' => $user->getId(),
      'role' => $user->getRole()?->value,
      'walletId' => $user->getWallet()?->getId(),
    ]);
  }



  #[Route('/{id<\d+>}', name: '_show', methods: ['GET'])]
  public function show(User $user): JsonResponse
  {
    return $this->json($this->userToArray($user));
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
>>>>>>> 9486a6af45cc327615f6924109b4eeae0dccb23b

    if (!$form->isValid()) {
      return $this->json([
        'errors' => (string) $form->getErrors(true, false),
      ], 422);
    }

<<<<<<< HEAD
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
=======
    $entityManager->flush();

    return $this->json($this->userToArray($user));
  }

  #[Route('/{id<\d+>}/delete', name: '_delete', methods: ['GET'])]
  public function delete(EntityManagerInterface $entityManager, User $user): JsonResponse
  {
    $entityManager->remove($user);
    $entityManager->flush();

    return $this->json(['message' => 'User deleted successfully'], 200);
  }
  private function userToArray(User $user): array
  {
    return [
      'id' => $user->getId(),
      'mail' => $user->getMail(),
      'role' => $user->getRole()?->value,
      'creationDate' => $user->getCreationDate()?->format('Y-m-d H:i:s'),
      'wallet' => $user->getWallet() ? [
        'id' => $user->getWallet()->getId(),
        'balance' => $user->getWallet()->getBalance(),
      ] : null,
    ];
>>>>>>> 9486a6af45cc327615f6924109b4eeae0dccb23b
  }
}
