<?php
require_once 'Dice.php';

class ProbabilityCalculator {
    public static function calculateProbabilities(array $dice): array {
        $n = count($dice);
        $result = [];

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($i === $j) {
                    $result[$i][$j] = '-';
                    continue;
                }
                $win = 0;
                $total = 0;
                foreach ($dice[$i]->sides as $a) {
                    foreach ($dice[$j]->sides as $b) {
                        if ($a > $b) $win++;
                        $total++;
                    }
                }
                $result[$i][$j] = round($win / $total, 2);
            }
        }

        return $result;
    }
}
