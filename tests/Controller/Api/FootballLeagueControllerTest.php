<?php

namespace App\Tests\Controller\Api;

use Symfony\Component\HttpFoundation\Response;

class FootballLeagueControllerTest extends ApiTestCase
{
    /**
     * @dataProvider dataProviderAuthenticatedRoutes
     * @param string $method
     * @param string $route
     */
    public function testRequiresAuthentication(string $method, string $route)
    {
        $this->assertRequiresAuthentication($method, $route);
    }

    public function dataProviderAuthenticatedRoutes(): array
    {
        return [
            'showAction' => ['GET', '/api/football-league/1'],
            'deleteAction' => ['DELETE', '/api/football-league/1'],
        ];
    }

    public function testShowFootballLeague()
    {
        $footballTeam1Data = [
            'name' => 'Arsenal',
            'strip' => 'Puma'
        ];

        $footballTeam2Data = [
            'name' => 'Liverpool',
            'strip' => 'New Balance'
        ];

        $footballTeam1 = $this->createFootballTeam(
            $footballTeam1Data['name'],
            $footballTeam1Data['strip']
        );

        $footballTeam1Data['id'] = $footballTeam1->getId();

        $footballTeam2 = $this->createFootballTeam(
            $footballTeam2Data['name'],
            $footballTeam2Data['strip']
        );

        $footballTeam2Data['id'] = $footballTeam2->getId();

        $footballLeague = $this->createFootballLeague(
            'Premier League',
            [$footballTeam1, $footballTeam2]
        );

        $footballLeagueId = $footballLeague->getId();

        $this->apiRequest(
            'GET',
            sprintf('/api/football-league/%s', $footballLeagueId)
        );

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('name', $responseData);
        $this->assertEquals('Premier League', $responseData['name']);

        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals($footballLeagueId, $responseData['id']);
        $this->assertArrayHasKey('footballTeams', $responseData);

        $responseFootballTeams = $responseData['footballTeams'];

        foreach ($responseFootballTeams as $responseFootballTeam) {
            $this->assertCount(3, $responseFootballTeam);
            $this->assertArrayHasKey('name', $responseFootballTeam);
            $this->assertArrayHasKey('strip', $responseFootballTeam);
            $this->assertArrayHasKey('id', $responseFootballTeam);
        }

        $this->assertFootballTeamResponse(
            $footballTeam1Data,
            $responseFootballTeams[0]
        );
        $this->assertFootballTeamResponse(
            $footballTeam2Data,
            $responseFootballTeams[1]
        );
    }

    public function testShowFootballLeagueForLeagueThatDoesNotExist()
    {
        $this->apiRequest(
            'GET',
            '/api/football-league/1'
        );

        $this->assertApiError(
            'Football League with id 1 not found',
            Response::HTTP_BAD_REQUEST,
            $this->client->getResponse()
        );
    }

    public function testDeleteFootballLeague()
    {
        $footballTeam1 = $this->createFootballTeam(
            'Liverpool',
            'New Balance'
        );

        $footballLeague = $this->createFootballLeague(
            'MLS',
            [$footballTeam1]
        );

        $footballLeagueId = $footballLeague->getId();

        $this->apiRequest(
            'DELETE',
            sprintf('/api/football-league/%s', $footballLeagueId)
        );

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertEquals('', $response->getContent());
    }

    public function testDeleteFootballLeagueThatDoesNotExist()
    {
        $footballLeague = $this->createFootballLeague(
            'MLS'
        );

        $nonExistentFootballLeagueId = $footballLeague->getId() - 1;

        $this->apiRequest(
            'DELETE',
            sprintf('/api/football-league/%s', $nonExistentFootballLeagueId)
        );

        $this->assertApiError(
            sprintf('Football League with id %s not found', $nonExistentFootballLeagueId),
            Response::HTTP_BAD_REQUEST,
            $this->client->getResponse()
        );
    }

    protected function assertFootballTeamResponse(
        array $expectedFootballTeamData,
        array $responseFootballTeamData
    ) {
        $this->assertEquals($expectedFootballTeamData['name'], $responseFootballTeamData['name']);
        $this->assertEquals($expectedFootballTeamData['strip'], $responseFootballTeamData['strip']);
        $this->assertEquals($expectedFootballTeamData['id'], $responseFootballTeamData['id']);
    }

}
