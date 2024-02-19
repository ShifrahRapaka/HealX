
<?php
error_reporting(0);
ini_set('display_errors', 0);
$result="";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Validate and sanitize the amount
    $amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_INT);

    if (is_numeric($amount) && isset($_COOKIE['appointmentid']) && isset($_COOKIE['slot'])) {
        $appointmentid = $_COOKIE['appointmentid'];
        $slot = $_COOKIE['slot'];

        // Perform database insertion
        $pdo = new PDO('mysql:host=localhost;dbname=id21596424_termproject', 'id21596424_root', 'R@kuten123');

        $payment_sql = 'INSERT INTO payment (appointmentid, paymentamount, day) VALUES (:appointmentid, :paymentamount, :day)';
        $payment_stmt = $pdo->prepare($payment_sql);
        $payment_stmt->bindParam(':appointmentid', $appointmentid);
        $payment_stmt->bindParam(':paymentamount', $amount);
        $payment_stmt->bindParam(':day', $slot);
        $payment_stmt->execute();
        //$payment_stmt->execute();

if ($payment_stmt->errorCode() !== '00000') {
    print_r($payment_stmt->errorInfo());
}
        // Set success message
        $_SESSION['result'] = 'Payment successful. Amount: $' . $amount;

        // Redirect to avoid form resubmission on page refresh
        header('Location: payment.php');
        exit;
    } else {
        $_SESSION['result'] = 'Invalid amount or missing appointment details';
    }
}
if(isset($_POST['redirect'])){
    header('Location: landing_page.php');
    exit();
}

// Redirect to the form page (PRG)
// unset_session();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
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

        input {
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
    </style>
</head>
<body>

    <h1>Payment Page</h1>

    <form action="payment.php" method="post">
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" placeholder="Enter the amount" required>

        <button type="submit" name='submit'>Confirm Payment</button>
        <button type='submit' name='redirect'>Go To Landing Page</button>
    </form>
    <?php
        echo $result;
        
    ?>

</body>
</html>
 