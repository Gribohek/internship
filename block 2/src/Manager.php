<?php
namespace App;

use App\Zoo\Zookeeper;
use App\Animal\AnimalFactory;
use App\Animal\Animal;

class Manager
{
    private Zookeeper $zookeeper;
    private AnimalFactory $factory;

    public function __construct(Zookeeper $zookeeper, AnimalFactory $factory)
    {
        $this->zookeeper = $zookeeper;
        $this->factory = $factory;
    }

    public function createAndPlaceAnimal(
        string $kingdom,
        int $legs = 0,
        int $tail = 0,
        int $wings = 0
    ): Animal {
        $animal = $this->factory->createAnimal($kingdom, $legs, $tail, $wings);
        $this->zookeeper->placeAnimal($animal);
        return $animal;
    }
}