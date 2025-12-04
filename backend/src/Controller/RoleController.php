<?php

namespace App\Controller;

use App\Entity\Role;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/roles')]
class RoleController extends AbstractController
{
    // ---------------------------------------------------------------------------------------------------
    // 1. POBIERANIE WSZYSTKICH RÓL (READ ALL)
    // GET /api/roles
    // ---------------------------------------------------------------------------------------------------
    #[Route('', name: 'app_role_index', methods: ['GET'])]
    public function index(RoleRepository $roleRepository): JsonResponse
    {
        $roles = $roleRepository->findAll();

        // Serializuj listę obiektów Role do formatu JSON
        return $this->json($roles, 200, [], ['groups' => 'role:read']);
    }

    // ---------------------------------------------------------------------------------------------------
    // 2. TWORZENIE NOWEJ ROLI (CREATE)
    // POST /api/roles
    // ---------------------------------------------------------------------------------------------------
    #[Route('', name: 'app_role_new', methods: ['POST'])]
    public function new(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        // Deserializuj JSON z Request do obiektu Role
        try {
            $role = $serializer->deserialize($request->getContent(), Role::class, 'json');
        } catch (\Exception $e) {
            return $this->json(['error' => 'Invalid JSON data: ' . $e->getMessage()], 400);
        }

        // Walidacja podstawowa (sprawdzenie, czy roleName nie jest puste)
        if (empty($role->getRoleName())) {
            return $this->json(['error' => 'Role name cannot be empty.'], 400);
        }

        // Zapisz do bazy danych
        $entityManager->persist($role);
        $entityManager->flush();

        // Zwróć nowo utworzony obiekt
        return $this->json($role, 201, [], ['groups' => 'role:read']);
    }

    // ---------------------------------------------------------------------------------------------------
    // 3. POBIERANIE POJEDYNCZEJ ROLI (READ ONE)
    // GET /api/roles/{id}
    // ---------------------------------------------------------------------------------------------------
    #[Route('/{id}', name: 'app_role_show', methods: ['GET'])]
    public function show(Role $role): JsonResponse
    {
        // Obiekt Role jest automatycznie pobierany przez ParamConverter
        return $this->json($role, 200, [], ['groups' => 'role:read']);
    }

    // ---------------------------------------------------------------------------------------------------
    // 4. AKTUALIZACJA ROLI (UPDATE)
    // PUT /api/roles/{id}
    // ---------------------------------------------------------------------------------------------------
    #[Route('/{id}', name: 'app_role_edit', methods: ['PUT'])]
    public function edit(Request $request, Role $role, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {
        // Aktualizuj istniejący obiekt Role danymi z JSON
        $serializer->deserialize($request->getContent(), Role::class, 'json', ['object_to_populate' => $role]);

        // Walidacja podstawowa
        if (empty($role->getRoleName())) {
            return $this->json(['error' => 'Role name cannot be empty.'], 400);
        }

        $entityManager->flush();

        return $this->json($role, 200, [], ['groups' => 'role:read']);
    }

    // ---------------------------------------------------------------------------------------------------
    // 5. USUWANIE ROLI (DELETE)
    // DELETE /api/roles/{id}
    // ---------------------------------------------------------------------------------------------------
    #[Route('/{id}', name: 'app_role_delete', methods: ['DELETE'])]
    public function delete(Role $role, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($role);
        $entityManager->flush();

        // Zwróć pustą odpowiedź z kodem 204 No Content
        return new JsonResponse(null, 204);
    }
}