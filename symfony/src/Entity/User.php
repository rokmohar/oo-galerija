<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table("user")
 * @ORM\HasLifecycleCallbacks()
 */
class User
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
     * @var null|string
     *
     * @ORM\Column(name="username", type="string", length=255)
     * @JMS\Type("string")
     */
    private $username;

    /**
     * @var null|string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @JMS\Type("string")
     */
    private $password;

    /**
     * @var null|string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @JMS\Type("string")
     */
    private $name;

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
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return self
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
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
     * @return ArrayCollection
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
