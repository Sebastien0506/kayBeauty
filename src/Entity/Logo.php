<?php

namespace App\Entity;

use App\Repository\LogoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: LogoRepository::class)]
#[Vich\Uploadable]
class Logo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   

    #[Vich\UploadableField(mapping: 'image_logo', fileNameProperty: 'imageName')]
    #[Assert\Image(
        maxSize: '20K',
        maxSizeMessage:"L'image est trop volumineuse ({{ size }} {{ suffix }}).La taille maximum autorisée est de {{ limit }} {{ suffix }}",
        mimeTypes: ["image/jpeg", "image/png", "image/jpg"],
        mimeTypesMessage: " Le format de l'image est invalide. Seul les formats JPEG, PNG et JPG sont acceptés."
    )]
     private ?File $imageFile = null;

    

    #[ORM\Column(nullable: true)]
     private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $imageName = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    

}
