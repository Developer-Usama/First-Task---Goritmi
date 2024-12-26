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

$error = '';
$message = '';

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (User::login($email, $password, $conn)) {
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } elseif (isset($_POST['forgot_password'])) {
        $email = $_POST['email'];

        if (User::checkUserExists($email, $conn)) {
            $resetToken = bin2hex(random_bytes(50));
            User::updateResetToken($email, $resetToken, $conn);

            if (sendResetEmail($email, $resetToken)) {
                $_SESSION['message'] = 'Password reset link sent to your email.';
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $error = 'Unable to send reset email. Please try again later.';
            }
        } else {
            $error = "No account found with that email.";
        }
    } elseif (isset($_POST['reset_password'])) {
        $token = $_POST['reset_token'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword === $confirmPassword) {
            if (User::resetPassword($token, $newPassword, $conn)) {
                $_SESSION['message'] = 'Password has been reset successfully.';
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $error = 'Invalid or expired token.';
            }
        } else {
            $error = "Passwords do not match.";
        }
    }
}

// Function to send reset email
function sendResetEmail($toEmail, $token)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your email';
        $mail->Password = 'your password';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('your email', 'Goritmi Com');
        $mail->addAddress($toEmail);

        $resetLink = "your-path-to-this-files/Task%20(Goritmi)/pages/reset_password.php?token=$token";
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = "<p>Click the link below to reset your password:</p>
                       <a href='$resetLink'>$resetLink</a>";
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Retrieve session messages
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>


<!-- html code starts -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form | Goritmi</title>

    <!-- nonito fonts -->
    <link rel="stylesheet" href="../assets/fonts/ninito-fonts.css">

    <!-- assets file's for styles-->
    <link rel="stylesheet" href="../assets/bootsrap/bootstrap.min.css">

    <style>
        /* Body Styling */
        body {
            background-color: #f7f7f7;
            font-family: "nunito", sans-serif !important;
        }

        /* Container Styling for Login Form */
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin-top: 100px;
            /* Adjusted for center positioning */
            margin-left: auto;
            margin-right: auto;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 20px;
            /* Adds space between fields */
        }

        /* Label Styling */
        .form-group label {
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
            display: block;
            /* Ensures labels are above inputs */
        }

        /* Input Fields */
        .form-group input {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            /* Prevents padding from affecting width */
        }

        /* Button Styling */
        .btn-primary {
            width: 100%;
            /* Makes button full width */
            padding: 12px;
            margin-bottom: .5rem;
            font-size: 16px;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            /* Darker blue on hover */
        }

        /* Link Styling */
        .forgot-link {
            display: block;
            text-align: center;
            margin: auto;
            color: #007bff;
            font-size: 14px;
            text-decoration: none;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* Optional: Add a small message under the form */
        .form-message {
            font-size: 12px;
            color: #666;
            text-align: center;
            margin-top: 15px;
        }

        /* Optional: Add animation to the form (fade in) */
        .container {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Responsive Styles */
        @media (min-width: 768px) {
            .container {
                max-width: 500px;
                /* Wider container on larger screens */
            }
        }

        .forgot-password-section {
            display: none;
        }

        /* Optional: Add animation to the modal */
        .modal.fade .modal-dialog {
            transform: translateY(0);
            transition: transform 0.5s ease-in-out;
        }

        /* Ensure modal remains in place when opened */
        .modal.show .modal-dialog {
            transform: translateY(0);
            /* Avoid sliding up */
        }

        .modal-header {
            background-color: #007bff;
            color: #fff;
            border-bottom: 2px solid #ddd;
        }

        .modal-body {
            background-color: #f9f9f9;
            padding: 20px;
        }

        .btn-close {
            color: white;
            background: transparent;
            border: none;
        }

        /* Optionally adjust the modal dialog for large screens */
        @media (min-width: 768px) {
            .modal-dialog {
                max-width: 500px;
            }
        }

        @media (max-width: 415px) {
            .container {
                max-width: 350px;
            }

        }

        @media (max-width: 320px) {
            .container {
                max-width: 290px;
            }

        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h2 class="text-center m-3 mb-4">Please Login to your Account</h2>

        <?php if ($error) {
            echo "<div class='alert alert-danger'>$error</div>";
        } ?>
        <?php if ($message) {
            echo "<div class='alert alert-success'>$message</div>";
        } ?>

        <!-- Login Form -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email or Name</label>
                <input type="text" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary">Login</button>
        </form>

        <p class="text-center"><a href="javascript:void(0);" data-toggle="modal" data-target="#forgotPasswordModal">Forgot Password?</a></p>
        <p class="text-center">Don't have an account? <a href="register.php">Register here</a></p>
    </div>

    <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forgotPasswordModalLabel">Forgot Password</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="email">Enter your Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" name="forgot_password" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- assets file's for scripts-->
    <script src="../assets/jquery/jquery-3.5.1.slim.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>


    <script>
        // Automatically close the modal after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $('#forgotPasswordModal').modal('hide');
            }, 5000); // 5000 ms = 5 seconds
        });
    </script>
</body>

</html>
