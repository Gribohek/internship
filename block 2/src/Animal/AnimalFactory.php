<?php
namespace App\Animal;

Class AnimalFactory{
    public function createAnimal(string $kingdom, int $legs=0,int $tail=0,int $wings=0):Animal{
        $animal=new Animal($kingdom);
        $animal->setLegs($legs);
        $animal->settail($tail);
        $animal->setWings($wings);
        return $animal;
    }   
}