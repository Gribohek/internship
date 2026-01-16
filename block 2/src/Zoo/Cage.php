<?php
namespace App\Zoo;

use App\Animal\Animal;

class Cage
{
    private $kingdom;
    private $animals = [];

    public function __construct($kingdom)
    {
        $this->kingdom = $kingdom;
    }

    public function addAnimal(Animal $animal):void
    {
        if ($animal->getKingdom() === $this->kingdom) {
            $this->animals[] = $animal;
        } else {
            echo "Нельзя поместить животное из другого царства!";
        }
    }

    public function getAnimals()
    {
        return $this->animals;
    }

       public function findAnimal(?int $legs = null, ?int $tail = null, ?int $wings = null): ?Animal
    {
        foreach ($this->animals as $animal) {
            if (
                ($legs === null || $animal->getLegs() === $legs) &&
                ($tail === null || $animal->getTail() === $tail) &&
                ($wings === null || $animal->getWings() === $wings)
            ) {
                return $animal;
            }
        }
        return null;
    }
}