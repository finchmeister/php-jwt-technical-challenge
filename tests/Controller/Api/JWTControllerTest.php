<?php


namespace App\Tests\Controller\Api;


use Symfony\Component\HttpFoundation\Response;

class JWTControllerTest extends ApiTestCase
{

    public function testLoginCheck()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"username":"bob","password":"password"}'
        );

        $response = $client->getResponse();

        $data = json_decode($response->getContent(), true);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertArrayHasKey('token', $data);
    }

}