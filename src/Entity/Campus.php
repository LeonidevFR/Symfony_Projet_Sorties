<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Unique;

/**
 * @ORM\Entity(repositoryClass=CampusRepository::class)
 */
class Campus
{

    public function __toString()
    {
        return $this->name;

    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Unique (message="Un campus portant ce nom existe déjà.")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="Campus")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Outings::class, mappedBy="Campus")
     */
    private $outings;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->outings = new ArrayCollection();
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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCampus($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCampus() === $this) {
                $user->setCampus(null);
            }
        }

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
            $outing->setCampus($this);
        }

        return $this;
    }

    public function removeOuting(Outings $outing): self
    {
        if ($this->outings->removeElement($outing)) {
            // set the owning side to null (unless already changed)
            if ($outing->getCampus() === $this) {
                $outing->setCampus(null);
            }
        }

        return $this;
    }

}
