<?php
require_once 'DiceParser.php';
require_once 'FairRandomGenerator.php';
require_once 'ProbabilityCalculator.php';
require_once 'TableRenderer.php';

class Game {
    private array $dice;
    private int $computerDieIndex;
    private int $userDieIndex;
    private bool $userFirst;

    public function __construct(array $argv) {
        $this->dice = DiceParser::parse($argv);
    }

    private function prompt(string $message): string {
        echo $message;
        return trim(fgets(STDIN));
    }

    public function start(): void {
        echo "Let's determine who makes the first move.\n";
        $fairGen = new FairRandomGenerator(2);
        echo "I selected a random value in the range 0..1 (HMAC={$fairGen->getHMAC()}).\n";

        while (true) {
            $input = $this->prompt("0 - 0\n1 - 1\nX - exit\n? - help\nYour selection: ");
            if (strtolower($input) === 'x') exit("Goodbye!\n");
            if ($input === '?') {
                $this->renderHelp();
                continue;
            }
            if (in_array($input, ['0', '1'])) {
                $userGuess = (int)$input;
                [$compChoice, $key] = $fairGen->reveal();
                echo "My selection: $compChoice (KEY=$key).\n";
                $this->userFirst = $userGuess === $compChoice;
                break;
            }
            echo "Invalid input.\n";
        }

        $this->chooseDice();
        $compRoll = $this->performRoll("my", $this->dice[$this->computerDieIndex]);
        $userRoll = $this->performRoll("your", $this->dice[$this->userDieIndex]);

        if ($compRoll > $userRoll) echo "I win ($compRoll > $userRoll)!\n";
        elseif ($userRoll > $compRoll) echo "You win ($userRoll > $compRoll)!\n";
        else echo "It's a tie ($userRoll == $compRoll)!\n";
    }

    private function chooseDice(): void {
        $availableIndices = range(0, count($this->dice) - 1);

        if ($this->userFirst) {
            echo "You go first.\n";
            while (true) {
                foreach ($availableIndices as $i) echo "$i - {$this->dice[$i]}\n";
                $input = $this->prompt("X - exit\n? - help\nYour selection: ");
                if (strtolower($input) === 'x') exit("Goodbye!\n");
                if ($input === '?') {
                    $this->renderHelp();
                    continue;
                }
                if (ctype_digit($input) && isset($this->dice[(int)$input])) {
                    $this->userDieIndex = (int)$input;
                    break;
                }
                echo "Invalid selection.\n";
            }
            $remaining = array_diff($availableIndices, [$this->userDieIndex]);
            $this->computerDieIndex = $remaining[array_rand($remaining)];
            echo "Computer selects {$this->dice[$this->computerDieIndex]}.\n";
        } else {
            $this->computerDieIndex = array_rand($availableIndices);
            echo "I go first and pick {$this->dice[$this->computerDieIndex]}.\n";
            while (true) {
                foreach ($availableIndices as $i) {
                    if ($i === $this->computerDieIndex) continue;
                    echo "$i - {$this->dice[$i]}\n";
                }
                $input = $this->prompt("X - exit\n? - help\nYour selection: ");
                if (strtolower($input) === 'x') exit("Goodbye!\n");
                if ($input === '?') {
                    $this->renderHelp();
                    continue;
                }
                if (ctype_digit($input) && isset($this->dice[(int)$input]) && (int)$input !== $this->computerDieIndex) {
                    $this->userDieIndex = (int)$input;
                    break;
                }
                echo "Invalid selection.\n";
            }
        }
    }

    private function performRoll(string $role, Dice $die): int {
        $fair = new FairRandomGenerator($die->getSidesCount());
        echo "\nIt's time for $role roll.\nHMAC: {$fair->getHMAC()}\n";

        while (true) {
            $input = $this->prompt("Add your number modulo {$die->getSidesCount()} (0 to " . ($die->getSidesCount() - 1) . "):\nX - exit\n? - help\nYour selection: ");
            if (strtolower($input) === 'x') exit("Goodbye!\n");
            if ($input === '?') continue;
            if (ctype_digit($input) && (int)$input >= 0 && (int)$input < $die->getSidesCount()) {
                $userVal = (int) $input;
                [$compVal, $key] = $fair->reveal();
                $resultIndex = $fair->getResult($userVal, $die->getSidesCount());
                echo "My number is $compVal (KEY=$key).\nFair number = ($compVal + $userVal) % {$die->getSidesCount()} = $resultIndex\n";
                echo "$role roll result is {$die->roll($resultIndex)}\n";
                return $die->roll($resultIndex);
            }
            echo "Invalid input.\n";
        }
    }

    private function renderHelp(): void {
        $probs = ProbabilityCalculator::calculateProbabilities($this->dice);
        TableRenderer::render($this->dice, $probs);
    }
}
