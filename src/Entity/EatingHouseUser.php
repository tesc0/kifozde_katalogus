<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EatingHouseUser
 *
 * @ORM\Table(name="eating_house_user")
 * @ORM\Entity
 */
class EatingHouseUser
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
     * @ORM\Column(name="user_id", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="eating_house_id", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $eatingHouseId;

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

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
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
