<?php

namespace App\Controller\Auth;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function __invoke(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true) ?? [];
        $username = $data['username'] ?? null;
        $plain = $data['password'] ?? null;

        if (!$username || !$plain) {
            return new JsonResponse(['message' => 'username i password są wymagane'], 400);
        }

        $exists = $em->getRepository(User::class)->findOneBy(['username' => $username]);
        if ($exists) {
            return new JsonResponse(['message' => 'użytkownik już istnieje'], 409);
        }

        $user = (new User())->setUsername($username);
        $user->setPassword($hasher->hashPassword($user, $plain));
        $em->persist($user);
        $em->flush();

        return new JsonResponse(['message' => 'ok'], 201);
    }
}
