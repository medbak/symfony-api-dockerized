<?php

namespace App\Tests\Feature;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateNotebookTest extends WebTestCase
{
    public function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = $this->createClient(['environment' => 'test']);
        $this->client->disableReboot();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $this->em->beginTransaction();
    }

    public function tearDown(): void
    {
        $this->em->rollback();
    }


    public function testCreateNotebook()
    {
        $requestData = [
            'identifier' => 'test identifier',
            'headline' => 'test headline',
            'content' => 'test content',
        ];

        $this->client->request('POST' , '/notebook', $requestData);

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
    }

    public function testInsertNotebookWithSameIdentifier()
    {
        $requestData = [
            'identifier' => 'test identifier',
            'headline' => 'test headline',
            'content' => 'test content',
        ];

        $this->client->request('POST' , '/notebook', $requestData);

        $this->client->getResponse()->getContent();

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $this->client->request('POST' , '/notebook', $requestData);

        $this->assertEquals('422', $this->client->getResponse()->getStatusCode());
    }
}