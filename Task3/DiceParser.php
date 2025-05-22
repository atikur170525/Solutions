<?php
require_once 'Dice.php';

class DiceParser {
    public static function parse(array $argv): array {
        if (count($argv) < 4) {
            exit("Error: You must provide at least three dice as arguments.\nExample: php play.php 2,2,4,4,9,9 1,1,6,6,8,8 3,3,5,5,7,7\n");
        }

        $diceSet = array_slice($argv, 1);
        $sidesCount = null;
        $parsed = [];

        foreach ($diceSet as $index => $dieStr) {
            $sides = explode(',', $dieStr);

            foreach ($sides as $side) {
                if (!ctype_digit($side)) {
                    exit("Error: Dice $index contains a non-integer value: '$side'.\n");
                }
            }

            if ($sidesCount === null) {
                $sidesCount = count($sides);
                if ($sidesCount < 2) {
                    exit("Error: Each die must have at least 2 sides.\n");
                }
            } elseif (count($sides) !== $sidesCount) {
                exit("Error: All dice must have the same number of sides. Die $index has " . count($sides) . " sides, expected $sidesCount.\n");
            }

            $parsed[] = new Dice(array_map('intval', $sides));
        }

        return $parsed;
    }
}
