<?php
require 'vendor/autoload.php';

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Get database connection details from .env
$dbHost = env('DB_HOST', '127.0.0.1');
$dbUsername = env('DB_USERNAME', 'root');
$dbPassword = env('DB_PASSWORD', '');
$dbDatabase = env('DB_DATABASE', 'desole');

$mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $dbDatabase);
if ($mysqli->connect_error) {
  die('Connect Error: ' . $mysqli->connect_error);
}

// Test 1: Show current estado of pedido 3
echo "=== BEFORE UPDATE ===" . PHP_EOL;
$result = $mysqli->query('SELECT id, estado FROM pedidos WHERE id = 3');
$row = $result->fetch_assoc();
echo "Pedido 3 estado: " . $row['estado'] . PHP_EOL;

// Test 2: Simulate the update query that the controller will run
$newEstado = 'preparando'; // Change to preparando
$updateQuery = "UPDATE pedidos SET estado = '{$newEstado}' WHERE id = 3";
echo PHP_EOL . "Running: " . $updateQuery . PHP_EOL;
$mysqli->query($updateQuery);

// Test 3: Check if it updated
echo PHP_EOL . "=== AFTER UPDATE ===" . PHP_EOL;
$result = $mysqli->query('SELECT id, estado FROM pedidos WHERE id = 3');
$row = $result->fetch_assoc();
echo "Pedido 3 estado: " . $row['estado'] . PHP_EOL;

if ($row['estado'] === 'preparando') {
  echo "✓ UPDATE SUCCESSFUL!" . PHP_EOL;
} else {
  echo "✗ UPDATE FAILED!" . PHP_EOL;
}

$mysqli->close();
?>
