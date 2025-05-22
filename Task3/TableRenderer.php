<?php

class TableRenderer {
    public static function render(array $dice, array $probabilities): void {
        echo "\nProbability Table (Winning chance for Dice[i] over Dice[j]):\n";
        echo str_pad(" ", 10);
        foreach ($dice as $i => $die) {
            echo str_pad("D$i", 10);
        }
        echo "\n";

        foreach ($probabilities as $i => $row) {
            echo str_pad("D$i", 10);
            foreach ($row as $val) {
                echo str_pad($val, 10);
            }
            echo "\n";
        }
    }
}
