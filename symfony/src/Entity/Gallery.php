<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GalleryRepository")
 * @ORM\Table("gallery")
 * @ORM\HasLifecycleCallbacks()
 */
class Gallery
{
    /**
     * @var null|UuidInterface
     *
     * @ORM\Id()
     * @ORM\Column(name="id", type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @JMS\ReadOnly()
     * @JMS\Type("uuid")
     */
    private $id;

    /**
     * @var null|User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="galleries")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     * @JMS\ReadOnly()
     * @JMS\Type("App\Entity\User")
     */
    private $author;

    /**
     * @var null|string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @var null|string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @JMS\Type("string")
     */
    private $description;

    /**
     * @var null|\DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @JMS\ReadOnly()
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $createdAt;

    /**
     * @var null|\DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @JMS\ReadOnly()
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $updatedAt;

    /**
     * @var ArrayCollection|Image[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="author")
     * @JMS\Exclude()
     */
    private $images;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    /**
     * @return null|UuidInterface
     */
    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    /**
     * @return null|User
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User $author
     * @return self
     */
    public function setAuthor(User $author): self
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     * @return self
     */
    public function setDescription(string $description = null): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return null|\DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return null|\DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return ArrayCollection|Image[]
     */
    public function getImages(): ArrayCollection
    {
        return $this->images;
    }

    /**
     * @param Image $image
     * @return self
     */
    public function addImage(Image $image): self
    {
        $this->images->add($image);
        return $this;
    }

    /**
     * @param Image $image
     * @return self
     */
    public function removeImage(Image $image): self
    {
        $this->images->removeElement($image);
        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist(): void
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }
}