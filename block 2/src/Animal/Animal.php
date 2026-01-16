<?php
namespace App\Animal;
class Animal
{
    private string $kingdom;
    private int $legs=0;
    private int $tail=0;
    private int $wings=0;

    public function __construct($kingdom)
    {
        $this->kingdom = $kingdom;      
    }
    public function getKingdom(): string { return $this->kingdom; }
    public function getLegs(): int { return $this->legs; }
    public function getTail(): int { return $this->tail; }
    public function getWings(): int { return $this->wings; }

    public function setLegs(int $legs): void { $this->legs = $legs; }
    public function setTail(int $tail): void { $this->tail = $tail; }
    public function setWings(int $wings): void { $this->wings = $wings; }

    public function describe():string
    {
        return "{$this->kingdom}: лап {$this->legs}, хвост {$this->tail}, крылья {$this->wings}";
    }
}