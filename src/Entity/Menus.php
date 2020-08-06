<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menus
 *
 * @ORM\Table(name="menus")
 * @ORM\Entity
 */
class Menus
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
     * @ORM\Column(name="week", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $week;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="smallint", nullable=false, options={"unsigned"=true,"comment"="1 = soup, 2 = main course, 3 = dessert, 4 = always"})
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="item", type="string", length=50, nullable=false)
     */
    private $item;

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

    public function getWeek(): ?int
    {
        return $this->week;
    }

    public function setWeek(int $week): self
    {
        $this->week = $week;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getItem(): ?string
    {
        return $this->item;
    }

    public function setItem(string $item): self
    {
        $this->item = $item;

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
