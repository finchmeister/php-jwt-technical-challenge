<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FootballTeamRepository")
 */
class FootballTeam
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"football-league"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"football-league"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"football-league"})
     */
    private $strip;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FootballLeague", inversedBy="footballTeams")
     */
    private $footballLeague;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStrip(): ?string
    {
        return $this->strip;
    }

    public function setStrip(string $strip): self
    {
        $this->strip = $strip;

        return $this;
    }

    public function getFootballLeague(): ?FootballLeague
    {
        return $this->footballLeague;
    }

    public function setFootballLeague(?FootballLeague $footballLeague): self
    {
        $this->footballLeague = $footballLeague;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'strip' => $this->getStrip(),
            'footballLeague' => $this->getFootballLeague() !== null
                ? $this->getFootballLeague()->getId()
                : null
        ];
    }
}
