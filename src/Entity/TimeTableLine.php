<?php

namespace App\Entity;

use App\Repository\TimeTableLineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeTableLineRepository::class)]
class TimeTableLine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $dateStart;

    #[ORM\Column(type: 'datetime')]
    private $dateEnd;

    #[ORM\ManyToOne(targetEntity: TimeTable::class, inversedBy: 'timeTableLine')]
    private $timeTable;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function getTimeTable(): ?TimeTable
    {
        return $this->timeTable;
    }

    public function setTimeTable(?TimeTable $timeTable): self
    {
        $this->timeTable = $timeTable;

        return $this;
    }
}
