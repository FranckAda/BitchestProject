<?php

namespace App\Entity;

use App\Repository\CryptoCurrencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CryptoCurrencyRepository::class)]
class CryptoCurrency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 10)]
    private ?string $symbol = null;

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'cryptoCurrency', orphanRemoval: true)]
    private Collection $Transaction;

    /**
     * @var Collection<int, cotation>
     */
    #[ORM\OneToMany(targetEntity: cotation::class, mappedBy: 'cryptoCurrency', orphanRemoval: true)]
    private Collection $cotation;

    public function __construct()
    {
        $this->Transaction = new ArrayCollection();
        $this->cotation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): static
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransaction(): Collection
    {
        return $this->Transaction;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->Transaction->contains($transaction)) {
            $this->Transaction->add($transaction);
            $transaction->setCryptoCurrency($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->Transaction->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCryptoCurrency() === $this) {
                $transaction->setCryptoCurrency(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, cotation>
     */
    public function getCotation(): Collection
    {
        return $this->cotation;
    }

    public function addCotation(cotation $cotation): static
    {
        if (!$this->cotation->contains($cotation)) {
            $this->cotation->add($cotation);
            $cotation->setCryptoCurrency($this);
        }

        return $this;
    }

    public function removeCotation(cotation $cotation): static
    {
        if ($this->cotation->removeElement($cotation)) {
            // set the owning side to null (unless already changed)
            if ($cotation->getCryptoCurrency() === $this) {
                $cotation->setCryptoCurrency(null);
            }
        }

        return $this;
    }
}
