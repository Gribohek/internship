<?php
require __DIR__ . '/vendor/autoload.php';

use App\Animal\AnimalFactory;
use App\Zoo\Zoo;
use App\Zoo\Zookeeper;
use App\Manager;

$zoo = new Zoo();
$zookeeper = new Zookeeper($zoo);
$factory = new AnimalFactory();
$manager = new Manager($zookeeper, $factory);

$manager->createAndPlaceAnimal('Звери', 4, 1, 0);
$manager->createAndPlaceAnimal('Птицы', 2, 1, 2);
$manager->createAndPlaceAnimal('Рыбы', 0, 1, 0);


$found = $zookeeper->findAnimal('Птицы', wings: 2);
echo $found ? $found->describe() : "Животное не найдено";