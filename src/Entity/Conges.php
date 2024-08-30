<?php

namespace App\Entity;

use App\Repository\CongesRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: CongesRepository::class)]
class Conges
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_debut_conge = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_fin_conge = null;

    #[ORM\ManyToOne(inversedBy: 'conges')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'conge', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\ManyToOne(inversedBy: 'conges')]
    private ?Label $label = null;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebutConge(): ?\DateTimeInterface
    {
        return $this->date_debut_conge;
    }

    public function setDateDebutConge(?\DateTimeInterface $date_debut_conge): static
    {
        $this->date_debut_conge = $date_debut_conge;

        return $this;
    }

    public function getDateFinConge(): ?\DateTimeInterface
    {
        return $this->date_fin_conge;
    }

    public function setDateFinConge(?\DateTimeInterface $date_fin_conge): static
    {
        $this->date_fin_conge = $date_fin_conge;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setConge($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getConge() === $this) {
                $commentaire->setConge(null);
            }
        }

        return $this;
    }

    public function getLabel(): ?Label
    {
        return $this->label;
    }

    public function setLabel(?Label $label): static
    {
        $this->label = $label;

        return $this;
    }



    // Autres propriétés...

    /**
     * @Assert\Callback
     */
    public function validateDates(ExecutionContextInterface $context): void
    {
        if ($this->date_fin_conge < $this->date_debut_conge) {
            $context->buildViolation('La date de fin ne peut pas être avant la date de début.')
                ->atPath('dateFin')
                ->addViolation();
        }
    }

    public function __toString(): string
    {
        return sprintf('Congé #%d: %s to %s', $this->getId(), $this->getDateDebutConge()->format('Y-m-d'), $this->getDateFinConge()->format('Y-m-d'));
    }
    


}
