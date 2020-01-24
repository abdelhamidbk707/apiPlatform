<?php

namespace App\Entity;

use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
/**
 * A book.
 *
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={"get","put","delete"},
 *     shortName="Book"
 * )
 */
class Book
{
    /**
     * @var int The id of this book.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null The ISBN of this book (or null if doesn't have one).
     *
     * @ORM\Column(nullable=true)
     */
    public $isbn;

    /**
     * @var string The title of this book.
     *
     * @ORM\Column
     */
    public $title;

    /**
     * @var string The description of this book.
     *
     * @ORM\Column(type="text")
     */
    public $description;

    /**
     * @var string The author of this book.
     *
     * @ORM\Column
     */
    public $author;

    /**
     * @var \DateTimeInterface The publication date of this book.
     *
     * @ORM\Column(type="datetime")
     */
    public $publicationDate;

    /**
     * @var Review[] Available reviews for this book.
     *
     * @ORM\OneToMany(targetEntity="Review", mappedBy="book", cascade={"persist", "remove"})
     */
    public $reviews;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setTextDescription(string $description): self
    {
        $this->description = nl2br($description);

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getPublicationDate(): \DateTimeInterface
    {
        return $this->publicationDate;
    }

    public function getPublishedAtAgo(): string
    {
        return Carbon::instance($this->getPublicationDate())->diffForHumans();
    }
}