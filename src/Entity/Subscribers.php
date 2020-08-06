<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subscribers
 *
 * @ORM\Table(name="subscribers")
 * @ORM\Entity
 */
class Subscribers
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true, options={"default"="NULL"})
     */
    private $email = 'NULL';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"default"="current_timestamp()"})
     */
    private $createdAt = 'current_timestamp()';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


}
