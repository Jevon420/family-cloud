<?php
// Simple script to test DB connection

$host = '127.0.0.1';
$port = 8889;
$database = 'family_cloud';
$username = 'root';
$password = 'root';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$database";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to database successfully!\n";

    // Try to get email configurations
    $stmt = $pdo->query("SELECT * FROM email_configurations LIMIT 10");
    $emailConfigs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($emailConfigs) > 0) {
        echo "Found " . count($emailConfigs) . " email configurations:\n";
        foreach ($emailConfigs as $index => $config) {
            echo "\nConfiguration #" . ($index + 1) . " (ID: " . $config['id'] . "):\n";
            echo "- Name: " . $config['name'] . "\n";
            echo "- Email: " . $config['email'] . "\n";
            echo "- SMTP Host: " . $config['smtp_host'] . "\n";
            echo "- SMTP Port: " . $config['smtp_port'] . "\n";
            echo "- SMTP Encryption: " . $config['smtp_encryption'] . "\n";
        }
    } else {
        echo "No email configurations found in the database.\n";
    }

} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}
