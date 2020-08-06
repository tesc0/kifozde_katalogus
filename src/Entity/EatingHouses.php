<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EatingHouses
 *
 * @ORM\Table(name="eating_houses")
 * @ORM\Entity
 */
class EatingHouses
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=50, nullable=false)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=5, nullable=false)
     */
    private $zip;

    /**
     * @var int
     *
     * @ORM\Column(name="district", type="smallint", nullable=false)
     */
    private $district;

    /**
     * @var string|null
     *
     * @ORM\Column(name="address", type="string", length=100, nullable=true, options={"default"="NULL"})
     */
    private $address = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="website", type="string", length=50, nullable=true, options={"default"="NULL"})
     */
    private $website = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="contact", type="string", length=50, nullable=true, options={"default"="NULL"})
     */
    private $contact = 'NULL';

    /**
     * @var string|null
     *
     * @ORM\Column(name="introduction", type="text", length=0, nullable=true, options={"default"="NULL"})
     */
    private $introduction = 'NULL';

    /**
     * @var float|null
     *
     * @ORM\Column(name="longitude", type="float", precision=9, scale=6, nullable=true, options={"default"="NULL"})
     */
    private $longitude = 'NULL';

    /**
     * @var float|null
     *
     * @ORM\Column(name="latitude", type="float", precision=9, scale=6, nullable=true, options={"default"="NULL"})
     */
    private $latitude = 'NULL';

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getDistrict(): ?int
    {
        return $this->district;
    }

    public function setDistrict(int $district): self
    {
        $this->district = $district;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(?string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

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
