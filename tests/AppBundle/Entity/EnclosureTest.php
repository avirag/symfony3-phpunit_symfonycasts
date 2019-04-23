<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Dinosaur;
use AppBundle\Exception\DinosaurAreRunningRampantException;
use AppBundle\Exception\NotABuffetException;
use PHPUnit\Framework\TestCase;
use AppBundle\Entity\Enclosure;

class EnclosureTest extends TestCase
{
    public function testItHasNoDinosaursByDefault()
    {
        $enclosure = new Enclosure();

        $this->assertEmpty($enclosure->getDinosaurs());
    }

    public function testItAddsDinosaurs()
    {
        $enclosure = new Enclosure(true);

        $enclosure->addDinosaur(new Dinosaur());
        $enclosure->addDinosaur(new Dinosaur());

        $this->assertCount(2, $enclosure->getDinosaurs());
    }

    public function testItDoesNotAllowMixDinosaurs()
    {
        $enclosure = new Enclosure(true);
        $enclosure->addDinosaur(new Dinosaur());

        $this->expectException(NotABuffetException::class);

        $enclosure->addDinosaur(new Dinosaur('Velociraptor', true));
    }

    /**
     * @expectedException AppBundle\Exception\NotABuffetException
     */
    public function testItDoesNotAllowToAddNon()
    {
        $enclosure = new Enclosure(true);
        $enclosure->addDinosaur(new Dinosaur('Velociraptor', true));
        $enclosure->addDinosaur(new Dinosaur());
    }

    public function testItDoesNotAllowToAddDinosaursToUnsecureEnclosure()
    {
        $enclosure = new Enclosure();

        $this->expectException(DinosaurAreRunningRampantException::class);
        $this->expectExceptionMessage('Are you craaazy!!??');

        $enclosure->addDinosaur(new Dinosaur());
    }
}
