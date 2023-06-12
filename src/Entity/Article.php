<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Ignore;


#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Le titre ne doit pas être vide.")]
    #[Assert\Length(
        min: 10,
        max: 100,
        minMessage: "Le titre doit comporter au moins {{ limit }} caractères.",
        maxMessage: "Le titre ne doit pas dépasser {{ limit }} caractères."
    )]
    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[Assert\NotBlank(message: "Le slug ne doit pas être vide.")]
    #[Assert\Length(
        min: 10,
        max: 100,
        minMessage: "Le slug doit comporter au moins {{ limit }} caractères.",
        maxMessage: "Le slug ne doit pas dépasser {{ limit }} caractères."
    )]
    #[ORM\Column(length: 100)]
    private ?string $slug = null;

    #[Assert\NotBlank(message: "Le contenu ne doit pas être vide.")]
    #[Assert\Length(
        min: 100,
        minMessage: "Le contenu doit comporter au moins {{ limit }} caractères."
    )]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?bool $published = false;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(name:"author_id", referencedColumnName:"id", onDelete:"SET NULL")]
    #[Ignore]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[Ignore]
    private ?Serie $serie = null;

    #[ORM\ManyToMany(targetEntity: Console::class, inversedBy: 'articles')]
    #[Ignore]
    private Collection $consoles;

    #[Vich\UploadableField(mapping: 'articles', fileNameProperty: 'imageName', size: 'imageSize')]
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

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Comment::class, cascade: ["persist"])]
    private Collection $comments;


    public function __construct()
    {
        $this->consoles = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
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

    public function getExcerpt(): ?string
    {
        return substr($this->content, 0, 150);
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getSerie(): ?Serie
    {
        return $this->serie;
    }

    public function setSerie(?Serie $serie): self
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * @return Collection<int, Console>
     */
    public function getConsoles(): Collection
    {
        return $this->consoles;
    }

    public function addConsole(Console $console): self
    {
        if (!$this->consoles->contains($console)) {
            $this->consoles->add($console);
        }

        return $this;
    }

    public function removeConsole(Console $console): self
    {
        $this->consoles->removeElement($console);

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

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $commentaire): self
    {
        if (!$this->comments->contains($commentaire)) {
            $this->comments->add($commentaire);
            $commentaire->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }


}
