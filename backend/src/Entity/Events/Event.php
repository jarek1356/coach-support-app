<?php

namespace App\Entity\Events;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\Events\EventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\Column(length: 255)]
    private ?string $eventType = null;

    #[ORM\Column]
    private ?bool $eventStatus = null;

    // --- DODANY KONSTRUKTOR ---
    public function __construct()
    {
        // Inicjalizacja startAt aktualną datą i czasem
        $this->startAt = new \DateTimeImmutable();
        
        // Inicjalizacja endAt na chwilę obecną. 
        // W realnej aplikacji zapewne ustawisz endAt na np. +1 godzinę od startAt.
        $this->endAt = new \DateTimeImmutable();
        
        // Domyślny status wydarzenia na false (np. 'Nieanulowane')
        $this->eventStatus = false; 
    }
    // --------------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getEventType(): ?string
    {
        return $this->eventType;
    }

    public function setEventType(string $eventType): static
    {
        $this->eventType = $eventType;

        return $this;
    }

    public function getEventStatus(): ?bool // Poprawiona nazwa metody
    {
        return $this->eventStatus;
    }

    public function setEventStatus(bool $eventStatus): static // Poprawiona nazwa metody
    {
        $this->eventStatus = $eventStatus;

        return $this;
    }
}