<?php

namespace App\Entity;

use App\Repository\SauvetageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SauvetageRepository::class)
 */
class Sauvetage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date_apparition;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateApparition(): ?\DateTimeInterface
    {
        return $this->date_apparition;
    }

    public function setDateApparition(\DateTimeInterface $date_apparition): self
    {
        $this->date_apparition = $date_apparition;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
