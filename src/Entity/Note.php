<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NoteRepository::class)
 */
class Note
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $perso;

    /**
     * @ORM\ManyToOne(targetEntity=Valeur::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $val;

    /**
     * @ORM\Column(type="integer")
     */
    private $biere_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPerso(): ?User
    {
        return $this->perso;
    }

    public function setPerso(?User $perso): self
    {
        $this->perso = $perso;

        return $this;
    }

    public function getVal(): ?Valeur
    {
        return $this->val;
    }

    public function setVal(?Valeur $val): self
    {
        $this->val = $val;

        return $this;
    }

    public function getBiereId(): ?int
    {
        return $this->biere_id;
    }

    public function setBiereId(int $biere_id): self
    {
        $this->biere_id = $biere_id;

        return $this;
    }
}
