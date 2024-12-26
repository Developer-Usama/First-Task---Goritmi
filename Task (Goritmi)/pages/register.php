<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

include('../config/db_conn.php');
include('../classes/user.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password == $confirmPassword) {

        if (User::checkUserExists($email, $conn)) {
            $error = "Email already exists!";
        } else {
            // Register the user
            User::register($name, $email, $phone, $gender, $password, $conn);

            // Send the welcome email
            if (sendWelcomeEmail($email, $name)) {
                $_SESSION['message'] = 'Registration successful! Please check your email.';
            } else {
                $_SESSION['message'] = 'Registration successful, but email could not be sent.';
            }
            header('Location: register.php');
            exit();
        }
    } else {
        $error = "Passwords do not match!";
    }
}

function sendWelcomeEmail($toEmail, $name)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'usamashafi0011@gmail.com';
        $mail->Password = 'dpna sxks bhgq kcut';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('usamashafi0011@gmail.com', 'Goritmi Com.');
        $mail->addAddress($toEmail, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Welcome to Our Website';
        $mail->Body = "<h1>Welcome, $name!</h1><p>Thank you for registering with us. We are excited to have you!</p>";
        $mail->AltBody = "Welcome, $name! Thank you for registering with us. We are excited to have you!";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form | Goritmi</title>

    <!-- nonito fonts -->
    <link rel="stylesheet" href="../assets/fonts/ninito-fonts.css">

    <!-- assets file's -->
    <link rel="stylesheet" href="../assets/bootsrap/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: "Nunito", serif;
            font-optical-sizing: auto;
            font-style: normal;
        }

        .container {
            width: 100%;
            max-width: 600px;
            /* Ensure container is not too wide */
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
        }

        .form-group label {
            font-weight: bold;
        }

        .alert {
            position: relative;
        }

        .alert-close {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        /* Make form inputs and button 100% width on small screens */
        .form-control {
            width: 100%;
        }

        /* Style the submit button */
        .btn-block {
            width: 100%;
        }

        /* Adjust the margin of text-center elements for smaller screens */
        .text-center {
            text-align: center;
        }

        /* For smaller devices like mobile */
        @media (max-width: 600px) {

            .container {
                padding: 15px;
                width: 90%;
            }

            .form-row {
                margin-bottom: 10px;
            }

            .form-group {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h2 class="text-center m-5">Please Register Your Account</h2>

        <?php if (isset($error)) {
            echo "<div class='alert alert-danger'>$error</div>";
        } ?>

        <?php if (isset($_SESSION['message'])) { ?>
            <div class='alert alert-success' id="success-message">
                <?php echo $_SESSION['message']; ?>
                <span class="alert-close" onclick="closeAlert()">×</span>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php } ?>

        <form method="POST" action="" onsubmit="return validateForm()">
            <div class="form-row">
                <div class="form-group col-12 col-md-6">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-12 col-md-6">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="gender">Gender</label>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-12 col-md-6">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group col-12 col-md-6">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>

        <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a></p>
    </div>

    <!-- assets file's for scripts -->
    <script src="../assets/jquery/jquery-3.5.1.slim.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

    <script>
        function validateForm() {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;

            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                return false;
            }

            return true;
        }

        function closeAlert() {
            var alert = document.getElementById('success-message');
            alert.style.display = 'none';
        }

        // Automatically close success message after 5 seconds
        setTimeout(function() {
            var alert = document.getElementById('success-message');
            if (alert) alert.style.display = 'none';
        }, 5000);
    </script>

</body>

</html>