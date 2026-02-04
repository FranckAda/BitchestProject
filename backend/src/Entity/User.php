<?php

namespace App\Entity;
use App\Enum\Roles;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $mail = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTime $creationDate = null;

    #[ORM\Column(enumType: Roles::class)]
    private ?Roles $role = null;

    #[ORM\OneToOne(mappedBy: 'clientId', cascade: ['persist', 'remove'])]
    private ?Wallet $wallet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getCreationDate(): ?\DateTime
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTime $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getRole(): ?Roles
    {
        return $this->role;
    }

    public function setRole(Roles $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): static
    {
        // unset the owning side of the relation if necessary
        if ($wallet === null && $this->wallet !== null) {
            $this->wallet->setClientId(null);
        }

        // set the owning side of the relation if necessary
        if ($wallet !== null && $wallet->getClientId() !== $this) {
            $wallet->setClientId($this);
        }

        $this->wallet = $wallet;

        return $this;
    }
}
