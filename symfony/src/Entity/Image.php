<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ORM\Table("gallery_image")
 * @ORM\HasLifecycleCallbacks()
 */
class Image
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
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="images")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     * @JMS\ReadOnly()
     * @JMS\Type("App\Entity\User")
     */
    private $author;

    /**
     * @var null|Gallery
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Gallery", inversedBy="images")
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id")
     * @JMS\ReadOnly()
     * @JMS\Type("App\Entity\Gallery")
     */
    private $gallery;

    /**
     * @var null|string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @JMS\Type("string")
     */
    private $title;

    /**
     * @var null|string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @JMS\Type("string")
     */
    private $description;

    /**
     * @var null|string
     *
     * @ORM\Column(name="customer", type="string", length=255, nullable=true)
     * @JMS\Type("string")
     */
    private $customer;

    /**
     * @var array
     *
     * @ORM\Column(name="tags", type="simple_array", nullable=true)
     * @JMS\Type("array<string>")
     */
    private $tags;

    /**
     * @var null|string
     *
     * @ORM\Column(name="mime_type", type="string", length=255)
     * @JMS\ReadOnly()
     * @JMS\Type("string")
     */
    private $mimeType;

    /**
     * @var null|string
     *
     * @ORM\Column(name="checksum", type="string", length=255)
     * @JMS\ReadOnly()
     * @JMS\Type("string")
     */
    private $checksum;

    /**
     * @var null|string
     *
     * @ORM\Column(name="extension", type="string", length=255)
     * @JMS\ReadOnly()
     * @JMS\Type("string")
     */
    private $extension;

    /**
     * @var null|string
     *
     * @ORM\Column(name="path", type="string", length=255)
     * @JMS\ReadOnly()
     * @JMS\Type("string")
     */
    private $path;

    /**
     * @var null|string
     *
     * @ORM\Column(name="web_path", type="string", length=255)
     * @JMS\ReadOnly()
     * @JMS\Type("string")
     */
    private $webPath;

    /**
     * @var null|int
     *
     * @ORM\Column(name="size", type="integer", options={"unsigned":true})
     * @JMS\ReadOnly()
     * @JMS\Type("int")
     */
    private $size;

    /**
     * @var null|int
     *
     * @ORM\Column(name="width", type="integer", options={"unsigned":true})
     * @JMS\ReadOnly()
     * @JMS\Type("int")
     */
    private $width;

    /**
     * @var null|int
     *
     * @ORM\Column(name="height", type="integer", options={"unsigned":true})
     * @JMS\ReadOnly()
     * @JMS\Type("int")
     */
    private $height;

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
     * @var null|string
     *
     * @JMS\Type("string")
     * @JMS\SkipWhenEmpty()
     */
    private $content;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->tags = array();
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
     * @return Gallery
     */
    public function getGallery(): ?Gallery
    {
        return $this->gallery;
    }

    /**
     * @param Gallery $gallery
     * @return self
     */
    public function setGallery(Gallery $gallery): self
    {
        $this->gallery = $gallery;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
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
     * @return null|string
     */
    public function getCustomer(): ?string
    {
        return $this->customer;
    }

    /**
     * @param null|string $customer
     * @return self
     */
    public function setCustomer(string $customer = null): self
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param null|array $tags
     * @return self
     */
    public function setTags(array $tags = null): self
    {
        if (null !== $tags) {
            $tags = array_unique($tags);
        }

        $this->tags = $tags;

        return $this;
    }

    /**
     * @param string $tag
     * @return self
     */
    public function addTag(string $tag): self
    {
        if (null === $this->tags) {
            $this->tags = array();
        }

        if (($key = array_search($tag, $this->tags, true)) === false) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    /**
     * @param string $tag
     * @return self
     */
    public function removeTag(string $tag): self
    {
        if (null !== $this->tags && ($key = array_search($tag, $this->tags, true)) !== false) {
            unset($this->tags[$key]);
        }

        return $this;
    }

    /**
     * @return null|string
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     * @return self
     */
    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getChecksum(): ?string
    {
        return $this->checksum;
    }

    /**
     * @param string $checksum
     * @return self
     */
    public function setChecksum(string $checksum): self
    {
        $this->checksum = $checksum;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getExtension(): ?string
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     * @return self
     */
    public function setExtension(string $extension): self
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return self
     */
    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getWebPath(): ?string
    {
        return $this->webPath;
    }

    /**
     * @param string $webPath
     * @return self
     */
    public function setWebPath(string $webPath): self
    {
        $this->webPath = $webPath;
        return $this;
    }

    /**
     * @return null|int
     */
    public function getSize(): ?int
    {
        return $this->size;
    }

    /**
     * @param int $size
     * @return self
     */
    public function setSize(int $size): self
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return null|int
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return self
     */
    public function setWidth(int $width): self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return null|int
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return self
     */
    public function setHeight(int $height): self
    {
        $this->height = $height;
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
     * @return null|string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     * @return self
     */
    public function setContent(string $content = null): self
    {
        $this->content = $content;
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
