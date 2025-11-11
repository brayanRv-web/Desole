<?php
require 'vendor/autoload.php';

$mysqli = new mysqli('localhost', 'root', 'Rovaz1304', 'desole');
if ($mysqli->connect_error) {
  die('Connect Error: ' . $mysqli->connect_error);
}

$result = $mysqli->query('SELECT id, estado FROM pedidos LIMIT 1');
if ($row = $result->fetch_assoc()) {
  echo "Pedido ID: " . $row['id'] . PHP_EOL;
  echo "Estado: " . ($row['estado'] ?? 'NULL') . PHP_EOL;
} else {
  echo "No pedidos found" . PHP_EOL;
}

$mysqli->close();
?>
