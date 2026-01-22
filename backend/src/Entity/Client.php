<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client extends User
{

    #[ORM\Column]
    private ?float $euroBalance = null;

    public function getEuroBalance(): ?float
    {
        return $this->euroBalance;
    }

    public function setEuroBalance(float $euroBalance): static
    {
        $this->euroBalance = $euroBalance;

        return $this;
    }
}
