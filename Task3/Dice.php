<?php

class Dice {
    public array $sides;

    public function __construct(array $sides) {
        $this->sides = $sides;
    }

    public function roll(int $index): int {
        return $this->sides[$index];
    }

    public function getSidesCount(): int {
        return count($this->sides);
    }

    public function __toString(): string {
        return '[' . implode(',', $this->sides) . ']';
    }
}
