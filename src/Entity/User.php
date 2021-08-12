<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    public function __toString()
    {
        return $this->getPseudo();

    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)

     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="Users")
     */
    private $campus;

    /**
     * @ORM\OneToMany(targetEntity=Outings::class, mappedBy="Author")
     */
    private $createdOutings;

    /**
     * @ORM\ManyToMany(targetEntity=Outings::class, mappedBy="Members")
     */
    private $outings;

    public function __construct()
    {
        $this->createdOutings = new ArrayCollection();
        $this->outings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhoneNumber(): ?String
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?String $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
       $metadata->addPropertyConstraint('pseudo', new Assert\Length([
            'min' => 3,
            'max' => 20,
            'minMessage' => 'Votre pseudo est trop court.',
            'maxMessage' => 'Votre pseudo est trop long.',
        ]));

        $metadata->addPropertyConstraint('email', new Assert\Email([
            'message' => 'L\'e-mail "{{ value }}" n\'est pas valide.',
        ]));

        $metadata->addPropertyConstraint('firstName', new Assert\Regex([
            'pattern' => '/\d/i',
            'match' => false,
            'message' => 'Votre prénom ne peut contenir de nombre.',
        ]));

        $metadata->addPropertyConstraint('lastName', new Assert\Regex([
            'pattern' => '/\d/i',
            'match' => false,
            'message' => 'Votre nom ne peut contenir de nombre.',
        ]));

        $metadata->addPropertyConstraint('phoneNumber', new Assert\Length([
            'max' => 10,
            'maxMessage' => 'Votre numéro de téléphone doit être composé de 10 chiffres.',
        ]));

        $metadata->addConstraint(new UniqueEntity([
            'fields' => 'pseudo',
            'message' => 'Ce pseudo est déjà utilisé.'
        ]));
    }

    /**
     * @return Collection|Outings[]
     */
    public function getCreatedOutings(): Collection
    {
        return $this->createdOutings;
    }

    public function addCreatedOuting(Outings $createdOuting): self
    {
        if (!$this->createdOutings->contains($createdOuting)) {
            $this->createdOutings[] = $createdOuting;
            $createdOuting->setAuthor($this);
        }

        return $this;
    }

    public function removeCreatedOuting(Outings $createdOuting): self
    {
        if ($this->createdOutings->removeElement($createdOuting)) {
            // set the owning side to null (unless already changed)
            if ($createdOuting->getAuthor() === $this) {
                $createdOuting->setAuthor(null);
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
            $outing->addMember($this);
        }

        return $this;
    }

    public function removeOuting(Outings $outing): self
    {
        if ($this->outings->removeElement($outing)) {
            $outing->removeMember($this);
        }

        return $this;
    }
}