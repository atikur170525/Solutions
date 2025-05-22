<?php

class RandomUtils {
    public static function generateSecureKey(int $bytes = 32): string {
        return bin2hex(random_bytes($bytes));
    }

    public static function generateSecureNumber(int $min, int $max): int {
        $range = $max - $min + 1;
        $maxInt = 256 ** 4;
        do {
            $random = unpack('N', random_bytes(4))[1];
        } while ($random >= floor($maxInt / $range) * $range);
        return $min + ($random % $range);
    }

    public static function computeHMAC(string $message, string $key): string {
        return hash_hmac('sha3-256', $message, $key);
    }
}
