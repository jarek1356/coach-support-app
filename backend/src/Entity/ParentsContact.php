<?php

namespace App\Entity;

use App\Repository\ParentsContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ParentsContactRepository::class)]
class ParentsContact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['contact:read', 'user:read'])]
    private ?int $id = null;

    // Relacja OneToOne z Użytkownikiem (Zawodnikiem)
    #[ORM\OneToOne(inversedBy: 'parentsContact', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $player = null; // Zawodnik, którego te dane dotyczą

    // --- Rodzic 1 (Obowiązkowy) ---

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Imię pierwszego rodzica jest wymagane.")]
    #[Groups(['contact:read', 'contact:write', 'user:read'])]
    private ?string $parent1FirstName = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Nazwisko pierwszego rodzica jest wymagane.")]
    #[Groups(['contact:read', 'contact:write', 'user:read'])]
    private ?string $parent1LastName = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: "Telefon pierwszego rodzica jest wymagany.")]
    #[Groups(['contact:read', 'contact:write', 'user:read'])]
    private ?string $parent1Phone = null;

    // --- Rodzic 2 (Opcjonalny) ---

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['contact:read', 'contact:write', 'user:read'])]
    private ?string $parent2FirstName = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['contact:read', 'contact:write', 'user:read'])]
    private ?string $parent2Phone = null;

    // --- GETTERY I SETTERY ---

    public function getId(): ?int
    {
        return $this->id;
    }

    // --- Player (Zawodnik) ---

    public function getPlayer(): ?User
    {
        return $this->player;
    }

    public function setPlayer(?User $player): static
    {
        $this->player = $player;

        return $this;
    }

    // --- Parent 1 ---

    public function getParent1FirstName(): ?string
    {
        return $this->parent1FirstName;
    }

    public function setParent1FirstName(string $parent1FirstName): static
    {
        $this->parent1FirstName = $parent1FirstName;

        return $this;
    }

    public function getParent1LastName(): ?string
    {
        return $this->parent1LastName;
    }

    public function setParent1LastName(string $parent1LastName): static
    {
        $this->parent1LastName = $parent1LastName;

        return $this;
    }

    public function getParent1Phone(): ?string
    {
        return $this->parent1Phone;
    }

    public function setParent1Phone(string $parent1Phone): static
    {
        $this->parent1Phone = $parent1Phone;

        return $this;
    }

    // --- Parent 2 ---

    public function getParent2FirstName(): ?string
    {
        return $this->parent2FirstName;
    }

    public function setParent2FirstName(?string $parent2FirstName): static
    {
        $this->parent2FirstName = $parent2FirstName;

        return $this;
    }

    public function getParent2Phone(): ?string
    {
        return $this->parent2Phone;
    }

    public function setParent2Phone(?string $parent2Phone): static
    {
        $this->parent2Phone = $parent2Phone;

        return $this;
    }
}