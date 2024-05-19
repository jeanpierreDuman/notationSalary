<?php

namespace App\Entity;

use App\Repository\SettingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
class Setting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'float')]
    private $startIntervalScore;

    #[ORM\Column(type: 'float')]
    private $endIntervalScore;

    #[ORM\Column(type: 'float')]
    private $amountScore;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartIntervalScore(): ?float
    {
        return $this->startIntervalScore;
    }

    public function setStartIntervalScore(float $startIntervalScore): self
    {
        $this->startIntervalScore = $startIntervalScore;

        return $this;
    }

    public function getEndIntervalScore(): ?float
    {
        return $this->endIntervalScore;
    }

    public function setEndIntervalScore(float $endIntervalScore): self
    {
        $this->endIntervalScore = $endIntervalScore;

        return $this;
    }

    public function getAmountScore(): ?float
    {
        return $this->amountScore;
    }

    public function setAmountScore(float $amountScore): self
    {
        $this->amountScore = $amountScore;

        return $this;
    }
}
