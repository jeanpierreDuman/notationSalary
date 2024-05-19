<?php

namespace App\Entity;

use App\Repository\SalaryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalaryRepository::class)]
class Salary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    private $lastname;

    #[ORM\OneToMany(mappedBy: 'salary', targetEntity: TimeTable::class)]
    private $timeTables;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'salaries')]
    private $company;

    #[ORM\Column(type: 'string', length: 255)]
    private $seniority;

    #[ORM\Column(type: 'string', length: 255)]
    private $socialSecurity;

    #[ORM\Column(type: 'date')]
    private $startAt;

    #[ORM\OneToMany(mappedBy: 'salary', targetEntity: PaySlip::class, orphanRemoval: true)]
    private $paySlip;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isScoreImpacted;

    #[ORM\ManyToOne(targetEntity: Job::class, inversedBy: 'salaries')]
    private $job;

    public function __construct()
    {
        $this->timeTables = new ArrayCollection();
        $this->paySlip = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return Collection<int, TimeTable>
     */
    public function getTimeTables(): Collection
    {
        return $this->timeTables;
    }

    public function addTimeTable(TimeTable $timeTable): self
    {
        if (!$this->timeTables->contains($timeTable)) {
            $this->timeTables[] = $timeTable;
            $timeTable->setSalary($this);
        }

        return $this;
    }

    public function removeTimeTable(TimeTable $timeTable): self
    {
        if ($this->timeTables->removeElement($timeTable)) {
            // set the owning side to null (unless already changed)
            if ($timeTable->getSalary() === $this) {
                $timeTable->setSalary(null);
            }
        }

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getSeniority(): ?string
    {
        return $this->seniority;
    }

    public function setSeniority(string $seniority): self
    {
        $this->seniority = $seniority;

        return $this;
    }

    public function getSocialSecurity(): ?string
    {
        return $this->socialSecurity;
    }

    public function setSocialSecurity(string $socialSecurity): self
    {
        $this->socialSecurity = $socialSecurity;

        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    /**
     * @return Collection<int, PaySlip>
     */
    public function getPaySlip(): Collection
    {
        return $this->paySlip;
    }

    public function addPaySlip(PaySlip $paySlip): self
    {
        if (!$this->paySlip->contains($paySlip)) {
            $this->paySlip[] = $paySlip;
            $paySlip->setSalary($this);
        }

        return $this;
    }

    public function removePaySlip(PaySlip $paySlip): self
    {
        if ($this->paySlip->removeElement($paySlip)) {
            // set the owning side to null (unless already changed)
            if ($paySlip->getSalary() === $this) {
                $paySlip->setSalary(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->lastname . ' ' . $this->firstname;
    }

    public function getIsScoreImpacted(): ?bool
    {
        return $this->isScoreImpacted;
    }

    public function setIsScoreImpacted(?bool $isScoreImpacted): self
    {
        $this->isScoreImpacted = $isScoreImpacted;

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }
}