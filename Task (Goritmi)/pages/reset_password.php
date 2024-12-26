<?php
// reset_password.php
include('../config/db_conn.php');
include('../classes/user.php');

$error = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword === $confirmPassword) {
        if (User::resetPassword($token, $newPassword, $conn)) {
            $message = 'Password reset successful.';
            header('Location: login.php');
            exit();
        } else {
            $error = 'Invalid or expired token.';
        }
    } else {
        $error = "Passwords do not match.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Goritmi</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/bootsrap/bootstrap.min.css">

    <style>
        /* Body Styling */
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }

        /* Main Container Styling */
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin-top: 100px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Header Styling */
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 20px;
        }

        /* Label Styling */
        .form-group label {
            font-weight: bold;
            color: #333;
            display: block;
        }

        /* Input Field Styling */
        .form-control {
            padding: 12px;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        /* Button Styling */
        .btn-success {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        /* Error and Success Alerts */
        .alert {
            font-size: 14px;
            text-align: center;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        /* Optional: Add a message for instructions */
        .support-message small {
            color: #333;
            font-size: 14px;
            text-align: center;
        }

        .support-message small a {
            color: #007bff;
            text-decoration: none;
        }

        .support-message small a:hover {
            text-decoration: underline;
        }


        /* Responsive Styles */
        @media (max-width: 767px) {
            .container {
                padding: 20px;
                margin-top: 60px;
                max-width: 90%;
            }

            h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Reset Password</h2>
        <?php if ($error) {
            echo "<div class='alert alert-danger'>$error</div>";
        } ?>
        <?php if ($message) {
            echo "<div class='alert alert-success'>$message</div>";
        } ?>
        <form method="POST" action="">
            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-success">Reset Password</button>
        </form>
        <div class="support-message mt-4 text-center">
            <small>If you have trouble, please contact support: <a href="mailto:info@goritmi.co.uk">info@goritmi.co.uk</a></small>
        </div>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <!-- assets file's for scripts -->
    <script src="../assets/jquery/jquery-3.5.1.slim.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

</body>

</html>