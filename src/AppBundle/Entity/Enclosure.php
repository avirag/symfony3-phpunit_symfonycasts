<?php


namespace AppBundle\Entity;


use AppBundle\Exception\DinosaurAreRunningRampantException;
use AppBundle\Exception\NotABuffetException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="enclosure")
 */
class Enclosure
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Dinosaur", mappedBy="enclosure", cascade={"persist"})
     */
    private $dinosaurs;

    /**
     * @var Collection|Security[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Security", mappedBy="enclosure", cascade={"persist"})
     */
    private $securities;

    /**
     * Enclosure constructor.
     */
    public function __construct(bool $withBasicSecurity = false)
    {
        $this->dinosaurs = new ArrayCollection();
        $this->securities = new ArrayCollection();

        if($withBasicSecurity) {
            $this->addSecurity(new Security('Fence', true, $this));
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection
     */
    public function getDinosaurs(): Collection
    {
        return $this->dinosaurs;
    }

    /**
     * @return Collection
     */
    public function getSecurities(): Collection
    {
        return $this->securities;
    }

    public function addDinosaur(Dinosaur $dinosaur)
    {
        if(!$this->canAddDinosaur($dinosaur)) {
            throw new NotABuffetException();
        }

        if(!$this->isSecurityActive()) {
            throw new DinosaurAreRunningRampantException('Are you craaazy!!??');
        }

        $this->dinosaurs[] = $dinosaur;
    }

    /**
     * @param Security $security
     */
    public function addSecurity(Security $security)
    {
        $this->securities[] = $security;
    }

    public function isSecurityActive(): bool
    {
        foreach ($this->securities as $security) {
            if($security->getIsActive()) {
                return true;
            }
        }

        return false;
    }

    private function canAddDinosaur(Dinosaur $dinosaur): bool
    {
        return count($this->dinosaurs) === 0
            || $this->dinosaurs->first()->isCarnivorous() === $dinosaur->isCarnivorous();
    }

    public function getDinosaurCount(): int
    {
        return $this->dinosaurs->count();
    }
}