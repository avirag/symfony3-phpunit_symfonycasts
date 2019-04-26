<?php


namespace Tests\AppBundle\Controller;


use AppBundle\DataFixtures\ORM\LoadBasicParkData;
use AppBundle\DataFixtures\ORM\LoadSecurityData;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testEnclosuresAreShownOnTheHomepage()
    {
        $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class
        ]);
        self::$kernel->getContainer()->get();

        $client = $this->makeClient();

        $crawler = $client->request('GET', '/');

        $this->assertStatusCode(200, $client);

        $table = $crawler->filter('.table-enclosures');
        $this->assertCount(3, $table->filter('tbody tr'));
    }
}