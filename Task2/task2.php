<?php

$dir = 'task2';
$email = ''; 
$hashes = [];

foreach (scandir($dir) as $file) {
    if ($file === '.' || $file === '..') continue;

    $path = $dir . DIRECTORY_SEPARATOR . $file;
    $binary = file_get_contents($path); 
    $hash = hash('sha3-256', $binary);  
    $hashes[] = strtolower($hash);      
}


rsort($hashes);

$joined = implode('', $hashes);

$finalInput = $joined . strtolower($email);

$finalHash = hash('sha3-256', $finalInput);

echo "!task2 {$email} {$finalHash}\n";
