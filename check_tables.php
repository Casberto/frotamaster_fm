<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=frotamaster', 'root', '');
    $stmt = $pdo->query('SHOW TABLES');
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables found:\n";
    foreach ($tables as $table) {
        echo "- " . $table . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
