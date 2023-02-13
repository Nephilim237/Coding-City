<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[Vich\Uploadable]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Slug(fields: ['title'])]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    private ?User $author = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[Assert\File(
        maxSize: '4Mi',
        mimeTypes: ['image/png', 'image/jpeg', 'image/webp'],
        maxSizeMessage: 'Fichier volumineux ({{ size }} {{ suffix }}). La taille maximale est {{ limit }} {{ suffix }}.',
        mimeTypesMessage: "Seul les images au format {{types}} sont autorisées.",
        uploadIniSizeErrorMessage: "Fichier volumineux. PHP autorise {{ limit }} {{ suffix }}."
    )]
    #[Vich\UploadableField(mapping: 'posts', fileNameProperty: 'image_illustration')]
    private ?File $postBanner = null;

    #[ORM\Column(length: 255)]
    private ?string $image_illustration = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $video = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'posts')]
    private Collection $category;

    #[ORM\Column(nullable: true)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column]
    private ?bool $active = null;

    public function __construct()
    {
        $this->category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImageIllustration(): ?string
    {
        return $this->image_illustration;
    }

    public function setImageIllustration(string $image_illustration): self
    {
        $this->image_illustration = $image_illustration;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): self
    {
        $this->video = $video;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getPostBanner(): ?File
    {
        return $this->postBanner;
    }

    /**
     * @param File|null $postBanner
     * @return Post
     */
    public function setPostBanner(?File $postBanner): Post
    {
        $this->postBanner = $postBanner;
        return $this;
    }
}
