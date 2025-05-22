<?php
require_once 'RandomUtils.php';

class FairRandomGenerator {
    private string $key;
    private int $computerValue;
    private string $hmac;

    public function __construct(int $range) {
        $this->key = RandomUtils::generateSecureKey();
        $this->computerValue = RandomUtils::generateSecureNumber(0, $range - 1);
        $this->hmac = RandomUtils::computeHMAC((string)$this->computerValue, $this->key);
    }

    public function getHMAC(): string {
        return $this->hmac;
    }

    public function reveal(): array {
        return [$this->computerValue, $this->key];
    }

    public function getResult(int $userValue, int $range): int {
        return ($this->computerValue + $userValue) % $range;
    }
}
