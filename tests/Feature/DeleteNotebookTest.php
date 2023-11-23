<?php

namespace App\Tests\Feature;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeleteNotebookTest extends WebTestCase
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

    public function testGetNotebook()
    {
        $requestData = [
            'identifier' => 'test identifier',
            'headline' => 'test headline',
            'content' => 'test content',
        ];

        $this->client->request('POST' , '/notebook', $requestData);

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $this->client->request('DELETE' , '/notebook/'.$data['notebook']['id']);
        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
    }

    public function testInsertNotebookWithSameIdentifier()
    {
        $this->client->request('DELETE' , '/notebook/999999');
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('Notebook 999999 not found to be deleted', $data['message']);
    }
}