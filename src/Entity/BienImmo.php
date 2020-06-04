<?php

namespace App\Entity;

use App\Repository\BienImmoRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=BienImmoRepository::class)
 */
class BienImmo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @ORM\Column(type="integer")
     */
    private $room;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datePublicationAddImmo;

    /**
     * @ORM\Column(type="integer")
     */
    private $area;

    /**
     * @ORM\Column(type="integer")
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $common;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $address;

    /**
     * @ORM\Column(type="boolean")
     */
    private $typeOfPropriete;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bienImmos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="boolean")
     */
    private $appartOrHouse;

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

    public function getRoom(): ?int
    {
        return $this->room;
    }

    public function setRoom(int $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDatePublicationAddImmo(): ?\DateTimeInterface
    {
        return $this->datePublicationAddImmo;
    }

    public function setDatePublicationAddImmo(\DateTimeInterface $datePublicationAddImmo): self
    {
        $this->datePublicationAddImmo = $datePublicationAddImmo;

        return $this;
    }

    public function getArea(): ?int
    {
        return $this->area;
    }

    public function setArea(int $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    public function setPostalCode(int $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCommon(): ?string
    {
        return $this->common;
    }

    public function setCommon(string $common): self
    {
        $this->common = $common;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getTypeOfPropriete(): ?bool
    {
        return $this->typeOfPropriete;
    }

    public function setTypeOfPropriete(bool $typeOfPropriete): self
    {
        $this->typeOfPropriete = $typeOfPropriete;

        return $this;
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

    public function getAppartOrHouse(): ?bool
    {
        return $this->appartOrHouse;
    }

    public function setAppartOrHouse(bool $appartOrHouse): self
    {
        $this->appartOrHouse = $appartOrHouse;

        return $this;
    }

}
