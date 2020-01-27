<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A book.
 *
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={"get","put","delete"},
 *     normalizationContext={"groups"={"book_listing:read"},"swagger_definition_name"="Read"},
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isPublished"})
 */
class Book
{
    /**
     * @var string
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
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
     * @Groups({"book_listing:read","book_listing:write"})
     * @ORM\Column
     */
    public $title;

    /**
     * @var string The description of this book.
     * @Groups({"book_listing:read"})
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
     * @ORM\Column(type="datetime")
     */
    public $publicationDate;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    public $isPublished;

    /**
     * @var Review[] Available reviews for this book.
     *
     * @ORM\OneToMany(targetEntity="Review", mappedBy="book", cascade={"persist", "remove"})
     */
    public $reviews;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function __construct(string $title)
    {
        $this->reviews = new ArrayCollection();
        $this->title = $title;
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

    /**
     * @Groups({"book_listing:read"})
     * @return string
     */
    public function getPublishedAtAgo(): string
    {
        return Carbon::instance($this->getPublicationDate())->diffForHumans();
    }
}