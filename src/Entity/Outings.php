<?php

namespace App\Entity;

use App\Repository\OutingsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
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
     * @Assert\GreaterThan("today")
     */
    private $dateHourOuting;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     * @Assert\LessThan("tomorrow")
     */
    private $dateInscriptionLimit;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     *
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
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="outings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="outings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="outings")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="createdOutings")
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="outings")
     */
    private $members;

    public function __construct()
    {
        $this->members = new ArrayCollection();
    }

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


    public function getCity(): ?city
    {
        return $this->city;
    }

    public function setCity(?city $city): self
    {
        $this->city = $city;

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('nameOuting', new Assert\Length([
            'min' => 6,
            'minMessage' => 'Le nom de la sortie doit faire minimum 6 caractères',
        ]));

        $metadata->addConstraint(new UniqueEntity([
            'fields' => 'nameOuting',
            'message' => 'Le nom de cette sortie est déjà utilisé.'
        ]));
    }

    public function getStatus(): ?status
    {
        return $this->status;
    }

    public function setStatus(?status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|user[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(user $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
        }

        return $this;
    }

    public function removeMember(user $member): self
    {
        $this->members->removeElement($member);

        return $this;
    }
}
