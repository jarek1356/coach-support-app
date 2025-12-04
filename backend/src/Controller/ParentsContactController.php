<?php

namespace App\Controller;

use App\Entity\ParentsContact;
use App\Repository\ParentsContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('/api/parents-contacts')]
class ParentsContactController extends AbstractController
{
    // ---------------------------------------------------------------------------------------------------
    // 1. POBIERANIE WSZYSTKICH KONTAKTÓW (READ ALL)
    // GET /api/parents-contacts
    // ---------------------------------------------------------------------------------------------------
    #[Route('', name: 'app_parents_contact_index', methods: ['GET'])]
    public function index(ParentsContactRepository $parentsContactRepository): JsonResponse
    {
        $contacts = $parentsContactRepository->findAll();

        // Używamy grupy 'contact:read'
        return $this->json($contacts, 200, [], ['groups' => 'contact:read']);
    }

    // ---------------------------------------------------------------------------------------------------
    // 2. TWORZENIE NOWEGO KONTAKTU (CREATE)
    // POST /api/parents-contacts
    // ---------------------------------------------------------------------------------------------------
    // Zazwyczaj kontakt jest tworzony razem z użytkownikiem, ale dodajemy tę metodę dla kompletności.
    // Wymagane jest, aby JSON zawierał pole 'player' z ID użytkownika.
    #[Route('', name: 'app_parents_contact_new', methods: ['POST'])]
    public function new(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): JsonResponse {
        // 1. Deserializacja JSON do obiektu ParentsContact
        try {
            // Używamy grupy 'contact:write' do przyjęcia danych
            $contact = $serializer->deserialize($request->getContent(), ParentsContact::class, 'json', [
                'groups' => ['contact:write']
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Nieprawidłowe dane JSON lub brak ID zawodnika.'], 400);
        }

        // 2. Walidacja encji
        $errors = $validator->validate($contact);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        // 3. Zapisz do bazy danych
        $entityManager->persist($contact);
        $entityManager->flush();

        // 4. Zwróć nowo utworzony obiekt
        return $this->json($contact, 201, [], ['groups' => 'contact:read']);
    }

    // ---------------------------------------------------------------------------------------------------
    // 3. POBIERANIE POJEDYNCZEGO KONTAKTU (READ ONE)
    // GET /api/parents-contacts/{id}
    // ---------------------------------------------------------------------------------------------------
    #[Route('/{id}', name: 'app_parents_contact_show', methods: ['GET'])]
    public function show(ParentsContact $contact): JsonResponse
    {
        return $this->json($contact, 200, [], ['groups' => 'contact:read']);
    }

    // ---------------------------------------------------------------------------------------------------
    // 4. AKTUALIZACJA KONTAKTU (UPDATE)
    // PUT /api/parents-contacts/{id}
    // ---------------------------------------------------------------------------------------------------
    #[Route('/{id}', name: 'app_parents_contact_edit', methods: ['PUT'])]
    public function edit(
        Request $request,
        ParentsContact $contact,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): JsonResponse {
        // 1. Aktualizuj istniejący obiekt danymi z JSON
        $serializer->deserialize($request->getContent(), ParentsContact::class, 'json', [
            AbstractNormalizer::OBJECT_TO_POPULATE => $contact,
            'groups' => ['contact:write']
        ]);

        // 2. Walidacja zaktualizowanej encji
        $errors = $validator->validate($contact);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        $entityManager->flush();

        return $this->json($contact, 200, [], ['groups' => 'contact:read']);
    }

    // ---------------------------------------------------------------------------------------------------
    // 5. USUWANIE KONTAKTU (DELETE)
    // DELETE /api/parents-contacts/{id}
    // ---------------------------------------------------------------------------------------------------
    #[Route('/{id}', name: 'app_parents_contact_delete', methods: ['DELETE'])]
    public function delete(ParentsContact $contact, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($contact);
        $entityManager->flush();

        return new JsonResponse(null, 204);
    }
}