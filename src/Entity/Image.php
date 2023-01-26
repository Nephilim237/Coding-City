<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[Vich\Uploadable]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\File(
        maxSize: '10Mi',
        mimeTypes: ['image/png', 'image/jpeg', 'image/webp'],
        maxSizeMessage: 'Fichier volumineux ({{ size }} {{ suffix }}). La taille maximale est {{ limit }} {{ suffix }}.',
        mimeTypesMessage: "Seul les images au format {{types}} sont autorisées.",
        uploadIniSizeErrorMessage: "Fichier volumineux. PHP autorise {{ limit }} {{ suffix }}."
    )]
    #[Assert\Image(
        allowLandscape: false,
    )]
    #[Vich\UploadableField(mapping: 'users', fileNameProperty: 'image')]
    private ?File $imageFile = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\OneToOne(inversedBy: 'image', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     * @return Image
     */
    public function setImageFile(?File $imageFile): Image
    {
        $this->imageFile = $imageFile;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getAvatarUri(?String $liipFilter = ""): ?String
    {
        $server_name = $_ENV['SERVER_DOMAIN'];
        return "{$server_name}/media/cache/{$liipFilter}/images/user/profile/{$this->getImage()}";
    }



    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'image' => $this->image,
        ];
    }

    public function __unserialize(array $serialized)
    {
        $this->id = $serialized['id'];
        $this->user = $serialized['user'];
        $this->image = $serialized['image'];
        return $this;
    }
}
