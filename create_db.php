<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'meditech', 'M3dit3ch');
    $pdo->exec('CREATE DATABASE IF NOT EXISTS e_kinerjaguru CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    echo "Database 'e_kinerjaguru' created successfully\n";
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
