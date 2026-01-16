<?php

namespace App\Controller\Auth;

use App\Entity\User\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
class UserInfo extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {}

    public function __invoke(): JsonResponse
    {
        /** @var User|null $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }


        $json = $this->serializer->serialize($user, 'json', [
            'groups' => ['user:read']
        ]);

    
        return new JsonResponse($json, 200, [], true);
    }
}