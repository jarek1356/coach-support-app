<?php

namespace App\Controller\Auth;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class RegisterController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly SerializerInterface $serializer
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setUsername($data['username'] ?? '');

        // Haszowanie hasła
        if (!empty($data['password'])) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        // --- OBSŁUGA RÓL ---
        if (!empty($data['roles']) && is_array($data['roles'])) {
            // Tutaj wpadnie ROLE_ADMIN lub ROLE_PARENT z Twojego JSONa
            $user->setRoles($data['roles']);
        } else {
            $user->setRoles([User::ROLE_PLAYER]);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Zwracamy czysty JSON z nowym użytkownikiem
        $json = $this->serializer->serialize($user, 'json', ['groups' => ['user:read']]);
        
        return new JsonResponse($json, 201, [], true);
    }
}