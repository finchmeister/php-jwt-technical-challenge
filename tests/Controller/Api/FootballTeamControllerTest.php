<?php

namespace App\Tests\Controller\Api;

use App\Entity\FootballLeague;
use App\Entity\FootballTeam;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FootballTeamControllerTest extends ApiTestCase
{

    public function testCreateNewFootballTeam()
    {
        $data = [
            'name' => 'Arsenal',
            'strip' => 'Puma'
        ];

        $this->apiRequest(
            'POST',
            '/api/football-team',
            $data
        );

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());

        // TODO: assert serialized response
    }

    public function testUpdateFootballTeam()
    {
        $footballTeam = $this->createFootballTeam('Arsenal', 'Puma');

        $footballTeamId = $footballTeam->getId();

        $data = [
            'name' => 'Arsenal',
            'strip' => 'Puma'
        ];

        $this->apiRequest(
            'PUT',
            sprintf('/api/football-team/%s', $footballTeamId),
            $data
        );

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }


}
