<?php

namespace App\Entity;

use App\Repository\OutingsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass=OutingsRepository::class)
 */
class Outings
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nameOuting;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $dateHourOuting;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    private $dateInscriptionLimit;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $spotNumber;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $duration;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=campus::class, inversedBy="outings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private $place;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $longitude;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameOuting(): ?string
    {
        return $this->nameOuting;
    }

    public function setNameOuting(string $nameOuting): self
    {
        $this->nameOuting = $nameOuting;

        return $this;
    }

    public function getDateHourOuting(): ?\DateTimeInterface
    {
        return $this->dateHourOuting;
    }

    public function setDateHourOuting(\DateTimeInterface $dateHourOuting): self
    {
        $this->dateHourOuting = $dateHourOuting;

        return $this;
    }

    public function getDateInscriptionLimit(): ?\DateTimeInterface
    {
        return $this->dateInscriptionLimit;
    }

    public function setDateInscriptionLimit(\DateTimeInterface $dateInscriptionLimit): self
    {
        $this->dateInscriptionLimit = $dateInscriptionLimit;

        return $this;
    }

    public function getSpotNumber(): ?string
    {
        return $this->spotNumber;
    }

    public function setSpotNumber(string $spotNumber): self
    {
        $this->spotNumber = $spotNumber;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCampus(): ?campus
    {
        return $this->campus;
    }

    public function setCampus(?campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(?string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

}
