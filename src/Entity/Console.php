<?php

namespace App\Entity;

use App\Repository\ConsoleRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Ignore;


#[ORM\Entity(repositoryClass: ConsoleRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
#[AsDoctrineListener(event: Events::preRemove, priority: 500, connection: 'default')]
class Console
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Le nom ne doit pas être vide.")]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères."
    )]
    #[ORM\Column(length: 30)]
    private ?string $name = null;

    #[Assert\NotBlank(message: "Le slug ne doit pas être vide.")]
    #[Assert\Length(
        max: 30,
        maxMessage: "Le slug ne doit pas dépasser {{ limit }} caractères."
    )]
    #[ORM\Column(length: 50)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'consoles')]
    #[Ignore]
    private Collection $articles;

    #[Vich\UploadableField(mapping: 'consoles', fileNameProperty: 'imageName', size: 'imageSize')]
    #[Assert\Image(maxSize: '4M', maxSizeMessage: "L'image ne doit pas dépasser {{ limit }}.")]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[Assert\NotBlank(message: "Le meta-title ne doit pas être vide.")]
    #[Assert\Length(
        min: 10,
        max: 80,
        minMessage: "Le meta-title doit comporter au moins {{ limit }} caractères.",
        maxMessage: "Le meta-title ne doit pas dépasser {{ limit }} caractères."
    )]
    #[ORM\Column(length: 80)]
    private ?string $metaTitle = null;

    #[Assert\NotBlank(message: "La meta-description ne doit pas être vide.")]
    #[Assert\Length(
        min: 80,
        max: 180,
        minMessage: "La meta-description doit comporter au moins {{ limit }} caractères.",
        maxMessage: "La meta-description ne doit pas dépasser {{ limit }} caractères."
    )]
    #[ORM\Column(length: 180)]
    private ?string $metaDescription = null;

    #[ORM\Column(length: 20)]
    private ?string $color = null;

    #[ORM\Column(options: ["default" => false])]
    private ?bool $display = false;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->addConsole($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            $article->removeConsole($this);
        }

        return $this;
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

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(string $metaTitle): self
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function isDisplay(): ?bool
    {
        return $this->display;
    }

    public function setDisplay(bool $display): self
    {
        $this->display = $display;

        return $this;
    }

}
