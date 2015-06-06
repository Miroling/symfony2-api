<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

class LabelControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Container
     */
    protected $container;

    public function setUp(){
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
    }

    protected function assertJsonResponse($response, $statusCode = 200) {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }

    public function testCreateLabel() {

        $data = array(
            "name" => "testName",
            "lat" => 0.3434,
            "lng" => 33.232,
            "description" => "Lorem ipsum"
        );
        $this->client->request('POST',
                               '/api/v1/labels.json',
                               array(),
                               array(),
                               array(),
                               json_encode($data)
        );
        $response = $this->client->getResponse();
        $content = $response->getContent();

        $this->assertJsonResponse($response, 200);

    }
}
