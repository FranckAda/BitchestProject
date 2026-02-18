<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Wallet;
use App\Enum\Roles;
use App\Form\UserType;
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

    $result = array_map(fn(User $u) => $this->userToArray($u), $users);

    return $this->json(['users' => $result]);
  }

  #[Route('/{id}', name: '_show', methods: ['GET'])]
  public function show(int $id, UserRepository $userRepository): JsonResponse
  {
    $user = $userRepository->find($id);

    if (!$user) {
      return $this->json(['message' => 'User not found'], 404);
    }

    return $this->json($this->userToArray($user));
  }

  #[Route('/new', name: '_create', methods: ['POST'])]
  public function create(Request $request, EntityManagerInterface $em): JsonResponse
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
        'errors' => (string) $form->getErrors(true, false),
      ], 422);
    }

    if ($user->getRole() === Roles::CLIENT && $user->getWallet() === null) {
      $wallet = new Wallet();
      $wallet->setBalance(500);
      $user->setWallet($wallet);
      $em->persist($wallet);
    }

    $em->persist($user);
    $em->flush();

    return $this->json($this->userToArray($user), 201);
  }


  #[Route('/{id}/edit', name: '_update', methods: ['PUT', 'PATCH'])]
  public function update(Request $request, EntityManagerInterface $entityManager, User $user): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
      return $this->json(['error' => 'Invalid JSON: ' . json_last_error_msg()], 400);
    }

    $form = $this->createForm(UserType::class, $user);

    $clearMissing = $request->getMethod() !== 'PATCH';
    $form->submit($data, $clearMissing);

    if (!$form->isValid()) {
      return $this->json([
        'errors' => (string) $form->getErrors(true, false),
      ], 422);
    }

    $entityManager->flush();

    return $this->json($this->userToArray($user));
  }
  #[Route('/{id<\d+>}/delete', name: '_delete', methods: ['DELETE'])]
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
  }
}
