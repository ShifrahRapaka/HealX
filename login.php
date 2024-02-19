<?php
//print_r($_COOKIE);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST['remember']=1;
    $username = $_POST['username'];
    $password = $_POST['password'];
    $servername = "localhost";
    $database='id21596424_termproject';
    try {
    $pdo = new PDO("mysql:host=".$servername.";dbname=".$database, "id21596424_root", 'R@kuten123');
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 //   echo "Connected successfully";
    } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    }
    $sql = 'SELECT username,password FROM user WHERE username = :username';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($account) {
    
        if (isset($_POST['remember'])) {
            $expiry_time = time() +  86400; 
            setcookie('username', $username, $expiry_time, '/');
        }header('Location: landing_page.php');
        exit;
    } else {
        echo 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <h2>Login Page</h2>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <!-- <input type="checkbox" id="remember" name="remember">
        <label for="remember">Remember me</label>
        <br> -->
        <input type="submit" value="Login">
    </form>
</body>
</html>