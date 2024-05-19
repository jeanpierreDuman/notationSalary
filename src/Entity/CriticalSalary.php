<?php

namespace App\Entity;

use App\Repository\CriticalSalaryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CriticalSalaryRepository::class)]
class CriticalSalary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Critical::class)]
    private $critical;

    #[ORM\ManyToOne(targetEntity: Salary::class)]
    private $salary;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCritical(): ?Critical
    {
        return $this->critical;
    }

    public function setCritical(?Critical $critical): self
    {
        $this->critical = $critical;

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
