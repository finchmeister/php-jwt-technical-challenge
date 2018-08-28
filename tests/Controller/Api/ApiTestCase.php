<?php


namespace App\Tests\Controller\Api;


use App\Entity\FootballLeague;
use App\Entity\FootballTeam;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApiTestCase extends WebTestCase
{
    /** @var Client */
    protected $client;
    /** @var EntityManagerInterface */
    protected $em;

    protected function setUp()
    {
        $this->client = $this->createAuthenticatedClient();
        // create db
        $this->em = $this->getEntityManager();
        $purger = new ORMPurger($this->em);
        $purger->purge();
    }


    protected function getService($id)
    {
        return $this->client->getContainer()->get($id);
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->getService('doctrine')->getManager();
    }

    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient(): Client
    {
        $client = static::createClient();
        $token = $client->getContainer()->get('lexik_jwt_authentication.encoder')->encode(['username' => 'bob']);
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $token));
        return $client;
    }


    protected function getAuthorisedHeaders($username, $headers = [])
    {
        $token = $this->getService('lexik_jwt_authentication.encoder')
            ->encode(['username' => $username]);
        $headers['Authorization'] = 'Bearer '. $token;
        return $headers;
    }

    protected function apiRequest(string $method, string $uri, array $data = null): void
    {
        $this->client->request(
            $method,
            $uri,
            [],
            [],
            $this->getAuthorisedHeaders('bob'),
            json_encode($data)
        );
    }

    /**
     * @param string $method
     * @param string $route
     */
    public function assertRequiresAuthentication(string $method, string $route)
    {
        $client = static::createClient();
        $client->request($method, $route);
        $this->assertSame(
            Response::HTTP_UNAUTHORIZED,
            $client->getResponse()->getStatusCode()
        );
    }

    protected function assertApiError(
        string $expectedMessage,
        int $expectedStatusCode,
        Response $response
    ) {
        $this->assertEquals($expectedMessage, json_decode($response->getContent()));
        $this->assertSame($expectedStatusCode, $response->getStatusCode());
    }

    protected function createFootballTeam(
        string $name,
        string $strip,
        FootballLeague $footballLeague = null
    ): FootballTeam {
        $footballTeam = new FootballTeam();
        $footballTeam->setName($name)->setStrip($strip);
        if ($footballLeague !== null) {
            $footballTeam->setFootballLeague($footballLeague);
        }
        $this->em->persist($footballTeam);
        $this->em->flush();
        return $footballTeam;
    }

    protected function createFootballLeague(
        string $name,
        array $footballTeams = []
    ) {
        $footballLeague = new FootballLeague();
        $footballLeague->setName($name);
        foreach ($footballTeams as $footballTeam) {
            $footballLeague->addFootballTeam($footballTeam);
        }
        $this->em->persist($footballLeague);
        $this->em->flush();
        return $footballLeague;
    }
}