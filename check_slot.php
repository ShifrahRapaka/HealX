<?php
$pdo = new PDO('mysql:host=localhost;dbname=id21596424_termproject','id21596424_root','R@kuten123');
if(!empty($_POST['slot'])){
    $slot=$_POST['slot'];
    $doctor = $_POST['doctor'];

    $query = "SELECT * FROM appointment WHERE slot=:slot";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':slot', $slot);
    $stmt->execute();
    $existing_slot = $stmt->fetch(PDO::FETCH_ASSOC);

    $query_doc = 'SELECT * FROM appointment WHERE doctorid = :doctor AND slot = :slot';
    $stmt2 = $pdo->prepare($query_doc);
    $stmt2->bindParam(':doctor', $doctor);
    $stmt2->bindParam(':slot', $slot);
    $stmt2->execute();
    $existing_doctor_slot = $stmt2->fetch(PDO::FETCH_ASSOC);

    if ($existing_slot || $existing_doctor_slot) {
        echo "<span style='color:red'>Slot Unavailable! Please choose another slot or doctor.</span>";
    } else {
        echo "<span style='color:green'>Slot Available! You can proceed to book the appointment.</span>";
    }
}
?>