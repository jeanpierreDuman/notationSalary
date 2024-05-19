<?php

namespace App\Entity;

use App\Repository\PaySlipLineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaySlipLineRepository::class)]
class PaySlipLine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $code;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'float')]
    private $base;

    #[ORM\Column(type: 'float')]
    private $amountSalary;

    #[ORM\Column(type: 'float')]
    private $amountEmploye;

    #[ORM\ManyToOne(targetEntity: PaySlip::class, inversedBy: 'paySlipLine')]
    private $paySlip;

    #[ORM\Column(type: 'float', nullable: true)]
    private $rateSalary;

    #[ORM\Column(type: 'float', nullable: true)]
    private $rateEmploye;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBase(): ?float
    {
        return $this->base;
    }

    public function setBase(float $base): self
    {
        $this->base = $base;

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

    public function getAmountEmploye(): ?float
    {
        return $this->amountEmploye;
    }

    public function setAmountEmploye(float $amountEmploye): self
    {
        $this->amountEmploye = $amountEmploye;

        return $this;
    }

    public function getPaySlip(): ?PaySlip
    {
        return $this->paySlip;
    }

    public function setPaySlip(?PaySlip $paySlip): self
    {
        $this->paySlip = $paySlip;

        return $this;
    }

    public function getRateSalary(): ?float
    {
        return $this->rateSalary;
    }

    public function setRateSalary(?float $rateSalary): self
    {
        $this->rateSalary = $rateSalary;

        return $this;
    }

    public function getRateEmploye(): ?float
    {
        return $this->rateEmploye;
    }

    public function setRateEmploye(?float $rateEmploye): self
    {
        $this->rateEmploye = $rateEmploye;

        return $this;
    }
}
