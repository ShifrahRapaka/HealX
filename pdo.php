

<?php
$servername = "localhost";
$u = "id21596424_root";
$p = "R@kuten123";
$database='id21596424_termproject';
try {
  $pdo = new PDO("mysql:host=".$servername.";dbname=".$database, $u, $p);
  // set the PDO error mode to exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>