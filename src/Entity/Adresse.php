<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $numeroDeRue = null;

    #[ORM\Column(length: 255)]
    private ?string $nomDeLaRue = null;

    #[ORM\ManyToOne(inversedBy: 'adresse')]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'adresse', targetEntity: Commande::class)]
    private Collection $commandes;

    #[ORM\Column]
    private ?float $codePostal = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    private ?string $numeroDeTelephone = null;

   

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroDeRue(): ?float
    {
        return $this->numeroDeRue;
    }

    public function setNumeroDeRue(float $numeroDeRue): self
    {
        $this->numeroDeRue = $numeroDeRue;

        return $this;
    }

    public function getNomDeLaRue(): ?string
    {
        return $this->nomDeLaRue;
    }

    public function setNomDeLaRue(string $nomDeLaRue): self
    {
        $this->nomDeLaRue = $nomDeLaRue;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setAdresse($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getAdresse() === $this) {
                $commande->setAdresse(null);
            }
        }

        return $this;
    }

    public function getCodePostal(): ?float
    {
        return $this->codePostal;
    }

    public function setCodePostal(float $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getNumeroDeTelephone(): ?string
    {
        return $this->numeroDeTelephone;
    }

    public function setNumeroDeTelephone(string $numeroDeTelephone): self
    {
        $this->numeroDeTelephone = $numeroDeTelephone;

        return $this;
    }

    
}
