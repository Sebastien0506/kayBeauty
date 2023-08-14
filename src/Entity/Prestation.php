<?php

namespace App\Entity;

use ImagePrestation;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PrestationRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Mime\MimeTypes;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: PrestationRepository::class)]
#[Vich\Uploadable]
class Prestation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'prestation', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\OneToMany(mappedBy: 'prestation', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[Vich\UploadableField(mapping: 'image_prestation', fileNameProperty: 'imageName')]
    #[Assert\Image(
        maxSize: '25K',
        maxSizeMessage:"L'image est trop volumineuse ({{ size }} {{ suffix }}).La taille maximum autorisée est de {{ limit }} {{ suffix }}",
        mimeTypes: ["image/jpeg", "image/png", "image/jpg"],
        mimeTypesMessage: " Le format de l'image est invalide. Seul les formats JPEG, PNG et JPG sont acceptés."
    )]
     private ?File $imageFile = null;

     #[ORM\Column(nullable: true)]
     private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
     private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
     private ?float $dureHeure = null;

     #[ORM\Column(nullable: true)]
     private ?int $dureMinutes = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        
        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;

    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setPrestation($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getPrestation() === $this) {
                $reservation->setPrestation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setPrestation($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPrestation() === $this) {
                $commentaire->setPrestation(null);
            }
        }

        return $this;
    }

    public function getDureHeure(): ?float
    {
        return $this->dureHeure;
    }

    public function setDureHeure(?float $dureHeure): self
    {
        $this->dureHeure = $dureHeure;

        return $this;
    }

    public function getDureMinutes(): ?int
    {
        return $this->dureMinutes;
    }

    public function setDureMinutes(?int $dureMinutes): self
    {
        $this->dureMinutes = $dureMinutes;

        return $this;
    }
}
