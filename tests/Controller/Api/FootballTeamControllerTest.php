<?php

namespace App\Tests\Controller\Api;

use Symfony\Component\HttpFoundation\Response;

class FootballTeamControllerTest extends ApiTestCase
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
            'newAction' => ['POST', '/api/football-team'],
            'updateAction' => ['PUT', '/api/football-team/1'],
        ];
    }

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

        $responseData = json_decode($response->getContent(), true);

        $this->assertExpectedFootballTeamResponse($data, $responseData);
    }

    public function testCreateNewFootballTeamWithBadData()
    {
        $data = [
            'steve' => 'Arsenal',
            'kit' => 'Puma'
        ];

        $this->apiRequest(
            'POST',
            '/api/football-team',
            $data
        );

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals('Invalid form', $responseData);
    }

    public function testCreateNewFootballTeamWithMalformedJson()
    {

        $this->client->request(
            'POST',
            '/api/football-team',
            [],
            [],
            [],
            '["bob","steve"'
        );

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals('Invalid json', $responseData);
    }

    public function testUpdateFootballTeam()
    {
        $footballTeam = $this->createFootballTeam('Arsenal', 'Puma');

        $footballTeamId = $footballTeam->getId();

        $data = [
            'name' => 'Burton Albion',
            'strip' => 'Reebok'
        ];

        $this->apiRequest(
            'PUT',
            sprintf('/api/football-team/%s', $footballTeamId),
            $data
        );

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);

        $this->assertExpectedFootballTeamResponse($data, $responseData);
    }

    protected function assertExpectedFootballTeamResponse(array $data, array $responseData)
    {
        $this->assertCount(4, $responseData);
        $this->assertArrayHasKey('name', $responseData);
        $this->assertEquals($data['name'], $responseData['name']);
        $this->assertArrayHasKey('strip', $responseData);
        $this->assertEquals($data['strip'], $responseData['strip']);
        $this->assertArrayHasKey('id', $responseData);
        if (isset($data['id'])) {
            $this->assertEquals($data['id'], $responseData['id']);
        }
        $this->assertArrayHasKey('footballLeague', $responseData);
    }

}
