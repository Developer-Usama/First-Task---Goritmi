<?php
session_start();
include('../config/db_conn.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
$user_phone = $_SESSION['user_phone'];
$user_gender = $_SESSION['user_gender'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Dashboard | Goritmi</title>

    <!-- assets file's -->
    <link rel="stylesheet" href="../assets/bootsrap/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            display: flex;
            margin: 0;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        /* Sidebar styling */
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: #fff;
            position: fixed;
            height: 100vh;
            padding-top: 20px;
            transition: transform 0.3s ease-in-out;
        }

        .sidebar h3 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            transition: all 0.3s;
        }

        .sidebar a:hover {
            background-color: #495057;
            color: #fff;
        }

        .sidebar a.active {
            background-color: #495057;
            color: #fff;
        }

        /* Sidebar for small screens (hidden by default) */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 1000;
            }

            .sidebar.show {
                transform: translateX(0);
            }
        }

        /* Main content styling */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            flex: 1;
            transition: margin-left 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }

        /* Header styling */
        .header {
            background-color: #343a40;
            color: #fff;
            padding: 15px;
            text-align: center;
            font-size: 1.2rem;
        }

        /* Toggler button */
        .toggler {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            background-color: #343a40;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            z-index: 1001;
            cursor: pointer;
        }

        .toggler i {
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .toggler {
                display: block;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h3>My Dashboard</h3>
        <a href="#" class="active"><i class="fa-solid fa-house"></i> Home</a>
        <a href="#"><i class="fa-solid fa-user"></i> Profile</a>
        <a href="#"><i class="fa-solid fa-envelope"></i> Messages</a>
        <a href="#"><i class="fa-solid fa-gear"></i> Settings</a>
        <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>

    <!-- Toggler Button -->
    <button class="toggler" id="toggler">
        <i class="fa-solid fa-bars"></i>
    </button>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <div class="header">
            Welcome, <?php echo $user_name; ?>!
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Your Account Details</h4>
            </div>
            <div class="card-body">
                <!-- User Info Table -->
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th scope="row">Full Name</th>
                            <td><?php echo $user_name; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Email</th>
                            <td><?php echo $user_email; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Phone</th>
                            <td><?php echo $user_phone; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Gender</th>
                            <td><?php echo ucfirst($user_gender); ?></td>
                        </tr>
                    </tbody>
                </table>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>

    <script>
        // Toggler functionality for small screens
        const toggler = document.getElementById('toggler');
        const sidebar = document.getElementById('sidebar');

        toggler.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });
    </script>


    <!-- assets file's for scripts -->
    <script src="../assets/jquery/jquery-3.5.1.slim.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

</body>

</html>