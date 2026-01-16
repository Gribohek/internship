<?php
namespace App\Zoo;

use App\Animal\Animal;

class Zookeeper
{
    private Zoo $zoo;

    public function __construct(Zoo $zoo)
    {
        $this->zoo = $zoo;
    }

    public function placeAnimal(Animal $animal): void
    {
        $cage = $this->zoo->getCage($animal->getKingdom());
        $cage->addAnimal($animal);
    }

    public function findAnimal(
        string $kingdom,
        ?int $legs = null,
        ?int $tail = null,
        ?int $wings = null
    ): ?Animal {
        $cage = $this->zoo->getCage($kingdom);
        return $cage->findAnimal($legs, $tail, $wings);
    }
}