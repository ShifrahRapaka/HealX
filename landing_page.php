<?php 
// print_r($_COOKIE);
//echo " You reached landing page";
// echo $_POST;
// echo $request->cookie;
if(isset($_POST['logout'])){
     session_start();

    // // Destroy the session variables
     session_unset();
     setcookie('username',$_COOKIE['username'],time()-86400,'/');
     session_destroy();
    
    // Redirect to the login page
    header("Location: ../index.php");
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Page</title>
</head>
<body>
    <h1>You are logged in</h1>
    <form action="../index.php" method="post">
        <button type="submit" name='logout'>Logout</button>
    </form>
</body>
</html>
<?php

$pdo = new PDO('mysql:host=localhost;dbname=id21596424_termproject','id21596424_root','R@kuten123');
$sql='select * from patient where username = :username';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':username',$_COOKIE['username']);
$stmt->execute();
$res=$stmt->fetch(PDO::FETCH_ASSOC);
if($res){
    echo '<button onclick="redirectToAnotherPage()">Book An Appointment</button><br>';
    echo '<script>
    // JavaScript function to handle the redirection
    function redirectToAnotherPage() {
        
        window.location.href = "appointments.php";
    }
</script>';
$appointment_sql='select appointment.patientid,appointment.doctorid,appointment.slot,doctor.username from appointment join doctor join patient on appointment.patientid=patient.patientid and appointment.doctorid = doctor.doctorid where patient.username = :username';
$appointment_stmt = $pdo->prepare($appointment_sql);
$appointment_stmt->bindParam(':username',$_COOKIE['username']);
$appointment_stmt->execute();
$rows=$appointment_stmt->fetchAll(PDO::FETCH_ASSOC);

echo ' <table border="2">';
foreach ( $rows as $row ) {
    echo ("<tr><td>");
    echo("</td><td>");
    echo($row['slot']);
    echo("</td><td>");
    echo($row['username']);
    echo("</td><td>");
    echo("</td></tr>\n");
    }
    echo '  </table>';
}
else{
 $appointment_sql='select appointment.patientid,appointment.doctorid,appointment.slot,patient.username from appointment join doctor join patient on appointment.patientid=patient.patientid and appointment.doctorid = doctor.doctorid where doctor.username = :username';
$appointment_stmt = $pdo->prepare($appointment_sql);
$appointment_stmt->bindParam(':username',$_COOKIE['username']);
$appointment_stmt->execute();
$rows=$appointment_stmt->fetchAll(PDO::FETCH_ASSOC);
// print_r($res1);
echo ' <table border="2">';
foreach ( $rows as $row ) {
    echo ("<tr><td>");
    echo("</td><td>");
    echo($row['slot']);
    echo("</td><td>");
    echo($row['username']);
    echo("</td><td>");
    // echo('<form method="post"><input type="hidden" name="adminID" value=""'.$row['adminID'].'">'."\n");

    // echo("\n</form>\n");
    echo("</td></tr>\n");
    }
    echo '  </table>';
}


?>
