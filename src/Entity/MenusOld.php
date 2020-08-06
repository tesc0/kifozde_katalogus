<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MenusOld
 *
 * @ORM\Table(name="menus_old")
 * @ORM\Entity
 */
class MenusOld
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="eating_house_id", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $eatingHouseId = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="week", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $week = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="monday", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $monday = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="tuesday", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $tuesday = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="wednesday", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $wednesday = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="thursday", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $thursday = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="friday", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $friday = 'NULL';

    /**
     * @var int|null
     *
     * @ORM\Column(name="created_at", type="integer", nullable=true, options={"default"="NULL"})
     */
    private $createdAt = 'NULL';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEatingHouseId(): ?int
    {
        return $this->eatingHouseId;
    }

    public function setEatingHouseId(?int $eatingHouseId): self
    {
        $this->eatingHouseId = $eatingHouseId;

        return $this;
    }

    public function getWeek(): ?int
    {
        return $this->week;
    }

    public function setWeek(?int $week): self
    {
        $this->week = $week;

        return $this;
    }

    public function getMonday(): ?int
    {
        return $this->monday;
    }

    public function setMonday(?int $monday): self
    {
        $this->monday = $monday;

        return $this;
    }

    public function getTuesday(): ?int
    {
        return $this->tuesday;
    }

    public function setTuesday(?int $tuesday): self
    {
        $this->tuesday = $tuesday;

        return $this;
    }

    public function getWednesday(): ?int
    {
        return $this->wednesday;
    }

    public function setWednesday(?int $wednesday): self
    {
        $this->wednesday = $wednesday;

        return $this;
    }

    public function getThursday(): ?int
    {
        return $this->thursday;
    }

    public function setThursday(?int $thursday): self
    {
        $this->thursday = $thursday;

        return $this;
    }

    public function getFriday(): ?int
    {
        return $this->friday;
    }

    public function setFriday(?int $friday): self
    {
        $this->friday = $friday;

        return $this;
    }

    public function getCreatedAt(): ?int
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?int $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }


}
