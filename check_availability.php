<?php
# create database connection
$dsn = "mysql:host=localhost;dbname=id21596424_termproject"; // change data for local deployment
$username = "id21596424_root";
$password = "R@kuten123";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (!empty($_POST["username"])) {
    $username = $_POST["username"];

    $query = "SELECT * FROM user WHERE username=:username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    $count = $stmt->rowCount();

    if ($count > 0) {
        echo "<span style='color:red'> Same Username Exist! Please use another username</span>";
    } else {
        echo "<span style='color:green'> You can use the username</span>";
    }
}
?>
