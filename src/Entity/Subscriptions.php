<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subscriptions
 *
 * @ORM\Table(name="subscriptions")
 * @ORM\Entity
 */
class Subscriptions
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
     * @var int
     *
     * @ORM\Column(name="eating_house_id", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $eatingHouseId;

    /**
     * @var int
     *
     * @ORM\Column(name="subscriber_id", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $subscriberId;

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

    public function getEatingHouseId(): ?int
    {
        return $this->eatingHouseId;
    }

    public function setEatingHouseId(int $eatingHouseId): self
    {
        $this->eatingHouseId = $eatingHouseId;

        return $this;
    }

    public function getSubscriberId(): ?int
    {
        return $this->subscriberId;
    }

    public function setSubscriberId(int $subscriberId): self
    {
        $this->subscriberId = $subscriberId;

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
