<?php

namespace App\Entity;

use App\Repository\TimeTableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeTableRepository::class)]
class TimeTable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToMany(mappedBy: 'timeTable', targetEntity: TimeTableLine::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private $timeTableLine;

    #[ORM\ManyToOne(targetEntity: Salary::class, inversedBy: 'timeTables')]
    private $salary;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    public function __construct()
    {
        $this->timeTableLine = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, TimeTableLine>
     */
    public function getTimeTableLine(): Collection
    {
        return $this->timeTableLine;
    }

    public function addTimeTableLine(TimeTableLine $timeTableLine): self
    {
        if (!$this->timeTableLine->contains($timeTableLine)) {
            $this->timeTableLine[] = $timeTableLine;
            $timeTableLine->setTimeTable($this);
        }

        return $this;
    }

    public function removeTimeTableLine(TimeTableLine $timeTableLine): self
    {
        if ($this->timeTableLine->removeElement($timeTableLine)) {
            // set the owning side to null (unless already changed)
            if ($timeTableLine->getTimeTable() === $this) {
                $timeTableLine->setTimeTable(null);
            }
        }

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
