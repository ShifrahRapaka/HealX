<?php
error_reporting(0);
ini_set('display_errors', 0);

?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset='UTF-8'/>
        <meta name="viewport" content='width=device-width , initial-scale=1.0' >
        <!-- <meta http-equiv='refresh-once' content='2; url= ../index.php' /> -->
        <title>HealX</title>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    </head>
    <body>
        <header>
            <h1>HealX</h1>
        </header>
        <main>
            <h1>REGISTER</h1>

            <form action="register.php" method="post" id="login_form" class="aligned"  >  
            <!-- changing the action from landing_page.php to self page -->
                <!-- changed the action tag above -->
                <input type="hidden" name="action" value="login">
                <label>Username:</label>
                <input type="text" class="text" name="username" id="username" onBlur='checkUsername()'>
                <span id='check-username'></span>
                <br>
                <label>Email:</label>
                <input type="email" class="text" name="email">
                <br>
                <label>Firstname:</label>
                <input type="text" class="text" name="fname">
                <br>
                <label>Lastname:</label>
                <input type="text" class="text" name="lname">
                <br>
                <label>Password:</label>
                <input type="password" class="text" name="password">
                <br>
                <label>Address:</label>
                <input type="text" class="text" name="address">
                <br>
                <label>Phone:</label>
                <input type="text" class="text" name="phone">
                <br>
                <label>
                <input type="checkbox" id="checkbox" name='checkbox'>
                <span>I am a doctor ( Please check this box only if you are a doctor! )</span>
                </label>
                <br>
                <label>Notes:</label>
                <input type="text" class="text" name="notes">
                <br>
                <br>

                <label>&nbsp;</label>
                <!-- <input type="submit" value="LOGIN" name='state' > -->
                <!-- <p><a href="login.php">register</a></p> -->
                <input type="submit" value="REGISTER" name='state'>
                
            </form>

            <p><?php echo $login_message; ?></p>
        </main>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script type="text/javascript">
    function checkUsername() {
            var usernameInput = $("#username").val();
            if (usernameInput.length > 0) {
                $("#msg").hide(); // Hide the paragraph
            } else {
                $("#msg").show(); // Show the paragraph
            }

            jQuery.ajax({
                url: "check_availability.php",
                data: 'username=' + usernameInput,
                type: "POST",
                success: function(data) {
                    $("#check-username").html(data);
                },
                error: function() {}
            });
       }
    </script>
    </body>
</html>
<?php

// print_r($_POST);
 $cookie_expiration_time =  time()+86400;
 if($_POST['email']!=''&&$_POST['password']!=''){
 $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING); // changed to username in the post 
 $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
 $encrypted_username = base64_encode($username);
 $encrypted_password = base64_encode($password);
// echo $username." ".$password;
 $cookie_expiration_time =  time()+86400;
 
 // Set the username and password cookies
 setcookie('username', $encrypted_username, $cookie_expiration_time, '/');
 setcookie('password', $encrypted_password, $cookie_expiration_time, '/');
 
 // Store the encrypted username and password in the session variables for future use
 $_SESSION['email'] = $encrypted_username;
 $_SESSION['password'] = $encrypted_password;
 

 }
 setcookie('state',$_POST['state'],$cookie_expiration_time,'/');
 $_SESSION['state']=$_POST['state'];
  // replacing the require once pdo file with actual shit 

  try {
    $pdo = new PDO("mysql:host=localhost;dbname=id21596424_termproject", 'id21596424_root', 'R@kuten123');
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";
  } catch(PDOException $e) {
    // echo "Connection failed: " . $e->getMessage();
  }
 if (isset($_POST['username']) && isset($_POST['password'])){
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $fname=filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
    $lname=filter_var($_POST['lname'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $address=filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
 }
 if(isset($_POST['checkbox'])){
    $isdoctor=1;
 }else{
    $isdoctor=0;
 }
 //echo $_POST['state'].' '.isset($_POST);

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $fname = filter_var($_POST['fname'], FILTER_SANITIZE_STRING);
    $lname = filter_var($_POST['lname'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    
    
    // Prepare the SQL query
    $sql = "insert into user (username, email, fname, lname, address, phone, isdoctor, password) values (:username, :email, :fname, :lname, :address, :phone, :isdoctor, :password)";
   
    try {
        // Prepare the statement
        $stmt = $pdo->prepare($sql);

        // Hash the password before inserting
        $password = password_hash($password, PASSWORD_DEFAULT);


        // Execute the query
        $stmt->execute(
            array(
                ':username' => $username,
                ':email' => $email,
                ':fname' => $fname,
                ':lname' => $lname,
                ':address' => $address,
                ':phone' => $phone,
                ':isdoctor' => $isdoctor,
                ':password' => $password

            )
        );

        // Get the last inserted ID
        if ($stmt->rowCount() > 0) {
            $_SESSION['success'] = 'User credentials pushed into database ';
    //        echo $_SESSION['success'];
        } else {
            $_SESSION['error'] = 'Registration failed';
     //       echo $_SESSION['error'];
        }
    } catch (PDOException $e) {
        echo "Error executing query: " . $e->getMessage();
    }
    if(isset($_POST['checkbox'])){
     //   echo 'CHECKING FOR THE DATA';
        $doctor_sql='select userid from user where username=:username';
        $stmt1=$pdo->prepare($doctor_sql);
        $stmt1->bindParam(':username', $username);
        $stmt1->execute(
            // array(
            //     ':username' => $username
            // )
        );
      //  echo 'CHECKed FOR THE DATA';
        $res = $stmt1->fetchAll(PDO::FETCH_ASSOC);
        // print_r($res);
    //    echo 'res is '.$res[0]['userid']; // result is extracted here continue tomorrow from here 
        $stmt2=$pdo->prepare('insert into doctor(userid,username,description) values (:userid,:username,:description)');
        $stmt2->bindParam(':userid',$res[0]['userid']);
        $stmt2->bindParam(':username',$username);
        $description = filter_var($_POST['notes'], FILTER_SANITIZE_STRING);
        $stmt2->bindParam(':description',$description);
        $stmt2->execute();
    }else{
        $patient_sql='select userid from user where username=:username';
        $stmt1=$pdo->prepare($patient_sql);
        $stmt1->bindParam(':username', $username);
        $stmt1->execute(
        );
     //   echo 'CHECKed FOR THE DATA';
        $res = $stmt1->fetchAll(PDO::FETCH_ASSOC);
     //   print_r($res);
     //   echo 'res is '.$res[0]['userid']; // result is extracted here continue tomorrow from here 
        $stmt2=$pdo->prepare('insert into patient(userid,username,notes) values (:userid,:username,:notes)');
        $stmt2->bindParam(':userid',$res[0]['userid']);
        $stmt2->bindParam(':username',$username);
        $description = filter_var($_POST['notes'], FILTER_SANITIZE_STRING);
        $stmt2->bindParam(':notes',$description);
        $stmt2->execute();
    }
}

    

?>