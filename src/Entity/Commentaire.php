<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reponseUser = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reponseAdmin = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?Conges $conge = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReponseUser(): ?string
    {
        return $this->reponseUser;
    }

    public function setReponseUser(?string $reponseUser): static
    {
        $this->reponseUser = $reponseUser;

        return $this;
    }

    public function getReponseAdmin(): ?string
    {
        return $this->reponseAdmin;
    }

    public function setReponseAdmin(?string $reponseAdmin): static
    {
        $this->reponseAdmin = $reponseAdmin;

        return $this;
    }

    public function getConge(): ?Conges
    {
        return $this->conge;
    }

    public function setConge(?Conges $conge): static
    {
        $this->conge = $conge;

        return $this;
    }


}
