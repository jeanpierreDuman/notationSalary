<?php

namespace App\Entity;

use App\Repository\PaySlipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaySlipRepository::class)]
class PaySlip
{
    const TYPE_BANK_TRANSFER = "bank_transfer";
    const TYPE_CHEQUE = "cheque";
    const TYPE_CASH = "cash";

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    private $startPeriod;

    #[ORM\Column(type: 'date')]
    private $endPeriod;

    #[ORM\Column(type: 'date')]
    private $payAt;

    #[ORM\Column(type: 'string', length: 255)]
    private $type;

    #[ORM\OneToMany(mappedBy: 'paySlip', targetEntity: PaySlipLine::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private $paySlipLine;

    #[ORM\Column(type: 'float')]
    private $amountEmploye;

    #[ORM\Column(type: 'float')]
    private $amountSalary;

    #[ORM\ManyToOne(targetEntity: Salary::class, inversedBy: 'paySlip')]
    #[ORM\JoinColumn(nullable: false)]
    private $salary;

    public function __construct()
    {
        $this->paySlipLine = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartPeriod(): ?\DateTimeInterface
    {
        return $this->startPeriod;
    }

    public function setStartPeriod(\DateTimeInterface $startPeriod): self
    {
        $this->startPeriod = $startPeriod;

        return $this;
    }

    public function getEndPeriod(): ?\DateTimeInterface
    {
        return $this->endPeriod;
    }

    public function setEndPeriod(\DateTimeInterface $endPeriod): self
    {
        $this->endPeriod = $endPeriod;

        return $this;
    }

    public function getPayAt(): ?\DateTimeInterface
    {
        return $this->payAt;
    }

    public function setPayAt(\DateTimeInterface $payAt): self
    {
        $this->payAt = $payAt;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, PaySlipLine>
     */
    public function getPaySlipLine(): Collection
    {
        return $this->paySlipLine;
    }

    public function addPaySlipLine(PaySlipLine $paySlipLine): self
    {
        if (!$this->paySlipLine->contains($paySlipLine)) {
            $this->paySlipLine[] = $paySlipLine;
            $paySlipLine->setPaySlip($this);
        }

        return $this;
    }

    public function removePaySlipLine(PaySlipLine $paySlipLine): self
    {
        if ($this->paySlipLine->removeElement($paySlipLine)) {
            // set the owning side to null (unless already changed)
            if ($paySlipLine->getPaySlip() === $this) {
                $paySlipLine->setPaySlip(null);
            }
        }

        return $this;
    }

    public function getAmountEmploye(): ?float
    {
        return $this->amountEmploye;
    }

    public function setAmountEmploye(float $amountEmploye): self
    {
        $this->amountEmploye = $amountEmploye;

        return $this;
    }

    public function getAmountSalary(): ?float
    {
        return $this->amountSalary;
    }

    public function setAmountSalary(float $amountSalary): self
    {
        $this->amountSalary = $amountSalary;

        return $this;
    }

    public function getSalary(): ?Salary
    {
        return $this->salary;
    }

    public function setSalary(?Salary $salary): self
    {
        $this->salary = $salary;

        return $this;
    }
}
