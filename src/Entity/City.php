<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $codePostal;

    /**
     * @ORM\OneToMany(targetEntity=Outings::class, mappedBy="City")
     */
    private $outings;

    /**
     * @ORM\OneToMany(targetEntity=Outings::class, mappedBy="codePostal")
     */
    private $outingsCodePostal;

    public function __construct()
    {
        $this->outings = new ArrayCollection();
        $this->outingsCodePostal = new ArrayCollection();
    }

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

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * @return Collection|Outings[]
     */
    public function getOutings(): Collection
    {
        return $this->outings;
    }

    public function addOuting(Outings $outing): self
    {
        if (!$this->outings->contains($outing)) {
            $this->outings[] = $outing;
            $outing->setCity($this);
        }

        return $this;
    }

    public function removeOuting(Outings $outing): self
    {
        if ($this->outings->removeElement($outing)) {
            // set the owning side to null (unless already changed)
            if ($outing->getCity() === $this) {
                $outing->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Outings[]
     */
    public function getOutingsCodePostal(): Collection
    {
        return $this->outingsCodePostal;
    }

    public function addOutingsCodePostal(Outings $outingsCodePostal): self
    {
        if (!$this->outingsCodePostal->contains($outingsCodePostal)) {
            $this->outingsCodePostal[] = $outingsCodePostal;
            $outingsCodePostal->setCodePostal($this);
        }

        return $this;
    }

    public function removeOutingsCodePostal(Outings $outingsCodePostal): self
    {
        if ($this->outingsCodePostal->removeElement($outingsCodePostal)) {
            // set the owning side to null (unless already changed)
            if ($outingsCodePostal->getCodePostal() === $this) {
                $outingsCodePostal->setCodePostal(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->name;

    }
}
