<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    //propriétés
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\LessThanOrEqual(20)
     */
    private $strength;

    /**
     * @ORM\Column(type="integer")
     * @Assert\LessThanOrEqual(100)
     */
    private $life;

    /**
     * @ORM\Column(type="integer")
     * @Assert\LessThanOrEqual(15)
     */
    private $armor;

    /**
     * @ORM\Column(type="text")
     *
     */
    private $description;

    //getters/setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function setStrength(int $strength): self
    {
        $this->strength = $strength;

        return $this;
    }

    public function getLife(): ?int
    {
        return $this->life;
    }

    public function setLife(int $life): self
    {
        $this->life = $life;

        return $this;
    }

    public function getArmor(): ?int
    {
        return $this->armor;
    }

    public function setArmor(int $armor): self
    {
        $this->armor = $armor;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    //******** un personnage en attaque un autre *************
    public function attack(Person $fighter) {
        $newLife = $fighter-> getLife() - ($this->getStrength() - $fighter-> getArmor());
        $fighter->setLife($newLife);

    }

    //******** Regénération des vies ***************
    public function heal() {
        $life = rand(1,10);
        $newLife = $this->getLife() + $life;
        $this->setLife($newLife);

    }
}
