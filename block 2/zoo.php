<?php
class Animal
{
    public $kingdom;
    public $legs;
    public $tail;
    public $wings;

    public function __construct($kingdom, $legs, $tail, $wings)
    {
        $this->kingdom = $kingdom;
        $this->legs = $legs;
        $this->tail = $tail;
        $this->wings = $wings;
    }
    public function describe()
    {
        return "{$this->kingdom}: лап {$this->legs}, хвост {$this->tail}, крылья {$this->wings}";
    }
}

class Beast extends Animal
{
    public function __construct($legs = 4, $tail = 1, $wings = 0)
    {
        return parent::__construct("Звери", $legs, $tail, $wings);
    }
}
class Bird extends Animal
{
    public function __construct($legs = 2, $tail = 1, $wings = 2)
    {
        return parent::__construct("Птицы", $legs, $tail, $wings);
    }
}
class Fish extends Animal
{
    public function __construct($legs = 0, $tail = 1, $wings = 0)
    {
        return parent::__construct("Рыбы", $legs, $tail, $wings);
    }
}

class Cage
{
    public $kingdom;
    public $animals = [];

    public function __construct($kingdom)
    {
        $this->kingdom = $kingdom;
    }

    public function addAnimal(Animal $animal)
    {
        if ($animal->kingdom === $this->kingdom) {
            $this->animals[] = $animal;
        } else {
            echo "Нельзя поместить животное из другого царства!";
        }
    }

    public function getAnimals()
    {
        return $this->animals;
    }

    public function findAnimal($legs, $tail, $wings)
    {
        foreach ($this->animals as $animal) {
            if ($animal->legs === $legs && $animal->tail === $tail && $animal->wings === $wings) {
                return $animal;
            }
        }
        return null;
    }
}
class Zoo
{
    public $cages = [];

    public function getCage($kingdom)
    {
        if (!isset($this->cages[$kingdom])) {
            $this->cages[$kingdom] = new Cage($kingdom);
        }
        return $this->cages[$kingdom];
    }
}
class Zookeeper
{
    private $zoo;

    public function __construct(Zoo $zoo)
    {
        $this->zoo = $zoo;
    }

    public function placeAnimal(Animal $animal)
    {
        $cage = $this->zoo->getCage($animal->kingdom);
        $cage->addAnimal($animal);
    }

    public function findAnimal($kingdom, $legs, $tail, $wings)
    {
        $cage = $this->zoo->getCage($kingdom);
        return $cage->findAnimal($legs, $tail, $wings);
    }
}
class Manager
{
    private $zoo;
    private $zookeeper;

    public function __construct(Zoo $zoo, Zookeeper $zookeeper)
    {
        $this->zoo = $zoo;
        $this->zookeeper = $zookeeper;
    }

    public function createAndPlaceAnimal($class)
    {
        $animal = new $class();
        $this->zookeeper->placeAnimal($animal);
        return $animal;
    }
}
$zoo = new Zoo();
$zookeeper = new Zookeeper($zoo);
$manager = new Manager($zoo, $zookeeper);

$manager->createAndPlaceAnimal(Beast::class);
$manager->createAndPlaceAnimal(Bird::class);
$manager->createAndPlaceAnimal(Fish::class);

$found = $zookeeper->findAnimal("Птицы", 2, 1, 2);
if ($found) {
    echo "Найдено: " . $found->describe();
}
