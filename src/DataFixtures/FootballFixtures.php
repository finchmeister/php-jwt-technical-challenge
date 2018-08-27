<?php


namespace App\DataFixtures;


use App\Entity\FootballLeague;
use App\Entity\FootballTeam;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FootballFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $premierLeague = new FootballLeague();
        $premierLeague->setName('Premier League');
        $manager->persist($premierLeague);

        $footballTeamCSV = $this->readFootballTeamCSV();
        foreach ($footballTeamCSV as $line) {
            $footballTeam = new FootballTeam();
            $footballTeam
                ->setName($line[0])
                ->setStrip($line[1])
                ->setFootballLeague($premierLeague)
            ;
            $manager->persist($footballTeam);
        }

        $manager->flush();
    }

    private function readFootballTeamCSV(): array
    {
        return array_map('str_getcsv', file(__DIR__.'/football-teams.csv'));
    }

}