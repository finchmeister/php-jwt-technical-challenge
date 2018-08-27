<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FootballLeagueRepository")
 */
class FootballLeague
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
     * @ORM\OneToMany(targetEntity="App\Entity\FootballTeam", mappedBy="footballLeague")
     * @Groups({"football-league"})
     */
    private $footballTeams;

    public function __construct()
    {
        $this->footballTeams = new ArrayCollection();
    }

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

    /**
     * @return Collection|FootballTeam[]
     */
    public function getFootballTeams(): Collection
    {
        return $this->footballTeams;
    }

    public function addFootballTeam(FootballTeam $team): self
    {
        if (!$this->footballTeams->contains($team)) {
            $this->footballTeams[] = $team;
            $team->setFootballLeague($this);
        }

        return $this;
    }

    public function removeFootballTeam(FootballTeam $team): self
    {
        if ($this->footballTeams->contains($team)) {
            $this->footballTeams->removeElement($team);
            // set the owning side to null (unless already changed)
            if ($team->getFootballLeague() === $this) {
                $team->setFootballLeague(null);
            }
        }

        return $this;
    }

    public function removeAllFootballTeams(): void
    {
        foreach ($this->getFootballTeams() as $footballTeam) {
            $this->removeFootballTeam($footballTeam);
        }
    }
}
