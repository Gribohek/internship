<?php
namespace App\Zoo;

class Zoo
{
    private array $cages = [];

    public function getCage(string $kingdom): Cage
    {
        if (!isset($this->cages[$kingdom])) {
            $this->cages[$kingdom] = new Cage($kingdom);
        }
        return $this->cages[$kingdom];
    }
}