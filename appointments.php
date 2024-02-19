<?php
error_reporting(0);
ini_set('display_errors', 0);
    $pdo = new PDO('mysql:host=localhost;dbname=id21596424_termproject','id21596424_root','R@kuten123');
    $sql='select * from doctor';
    $stmt=$pdo->prepare($sql);
    $stmt->execute();
    $names=$stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php
 //echo $_POST['username'].' '.$_POST['docname'].' '.$_POST['date'].' '.$_POST['time']; 

 $slot=$_POST['date'].''.$_POST['time'];
 

 $pat_sql='select patientid from patient where username = :username';
 $pat_stmt=$pdo->prepare($pat_sql);
 $pat_stmt->bindParam(':username', $_POST['username']);
 $pat_stmt->execute();
 $pat_res=$pat_stmt->fetchAll(PDO::FETCH_ASSOC);
//  print_r( $pat_res[0]['patientid']);
//  echo $pat_res[0];

 $doc_sql = 'select doctorid from doctor where username = :username';
 $doc_stmt=$pdo->prepare($doc_sql);
 $doc_stmt->bindParam(':username', $_POST['docname']);
 $doc_stmt->execute();
 $doc_res=$doc_stmt->fetchAll(PDO::FETCH_ASSOC);
// print_r( $doc_res);



if(isset($_POST['submit'])){
$appointment_sql='insert into appointment(patientid, doctorid, slot) values (:patientid, :doctorid, :slot)';
$appointment_stmt=$pdo->prepare($appointment_sql);
$appointment_stmt->bindParam(':patientid',$pat_res[0]['patientid']);
$appointment_stmt->bindParam(':doctorid',$doc_res[0]['doctorid']);
$appointment_stmt->bindParam(':slot',$slot);
$appointment_stmt->execute();

$aptid='select appointmentid from appointment where doctorid = :doctorid and slot = :slot';
$aptstmt=$pdo->prepare($aptid);
$aptstmt->bindParam(':doctorid',$doc_res[0]['doctorid']);
$aptstmt->bindParam(':slot',$slot);
$aptstmt->execute();
$aptres=$aptstmt->fetchAll(PDO::FETCH_ASSOC);
setcookie('appointmentid',$aptres[0]['appointmentid'],time()+86400,'/');
setcookie('slot',$slot,time()+86400,'/');
// header("Location: payment.php");
// exit();
}
if(isset($_POST['submit'])){
    echo 'redirecting';
    header("Location: payment.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments Page</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        form {
            max-width: 400px;
            margin: auto;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
        #name{
            font-size:20px;
        }
    </style>
</head>
<body>

    <h1>Book an Appointment</h1>
    
    <form action='appointments.php' method='post'id="appointmentForm">
        <label for="username">User Name:</label>
        <input type="text" id="username" name="username" required>
        <label for="name">Choose Doctor:</label>
        <select id="name" name="docname">
            <?php

            foreach ($names as $name) {
                echo "<option value='" . $name['username'] . "'>" . $name['username'] . "</option>";
            }
            ?>
        </select>


        <label for="date">Appointment Date:</label>
        <input type="date" id="date" name="date" required>
        <label for="time">Time (All times should be ending in 00 or 30):</label>
        <input type="time" id="time" name="time" onBlur="checkSlot()" required>
        <span id='check-slot'></span>
        <br>


        <button  type="submit" name='submit'  >Confirm Appointment and make Payment</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script type="text/javascript">
    function checkSlot() {
            var slot = $("#date").val() + $('#time').val();
            var doctor = $("#name").val();
            jQuery.ajax({
                url: "check_slot.php",
             
                data: {
                'slot': slot,
                'doctor': doctor
            },
                type: "POST",
                success: function(data) {
                    $("#check-slot").html(data);
                },
                error: function() { }
            });
        }
    </script>
</body>
</html>
