<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=65)
     * @Assert\Length(
     *  min=5,
     *  max=65,
     *  minMessage= "Votre titre doit au moins avoir {{ limit }} caractères.",
     *  maxMessage= "Votre titre ne doit pas dépasser {{ limit }} caractères."
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    private $upperTitle;

    /**
     * Undocumented variable
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     * @Assert\File(
     *  mimeTypes={"image/png", "image/jpeg"},
     *  mimeTypesMessage =" Seuls les types {{ types }} sont acceptés."
     * )
     */
    private $image;

    public function __construct()
    {
        $this->upperTitle =  strtoupper($this->title);
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get the value of upperTitle
     */
    public function getUpperTitle()
    {
        $this->upperTitle =  strtoupper($this->title);
        return $this->upperTitle;
    }

    /**
     * Set the value of upperTitle
     */
    public function setUpperTitle($upperTitle): self
    {
        $this->upperTitle = $upperTitle;

        return $this;
    }

    /**
     * Get the value of image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     */
    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }
}
