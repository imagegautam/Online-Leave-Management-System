<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Online Leave Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .header {
            background-color: #34495e;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
			background-image: url('../images/sidebar.jpg');
        }
        .header h1 {
            margin: 0;
        }
        .container {
            margin: 50px;
            background-color: white;
            padding: 100px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
			width: 400px;
			margin-left: 450px;
			margin-right: 500px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        .footer {
            background-color: #34495e;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
			background-image: url('../images/sidebar.jpg');
        }
		.header {
            background-color: #34495e;
            color: white;
            padding: 2px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
        }
        .nav-links {
            display: flex;
            gap: 20px;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .nav-links a:hover {
            background-color: #2c3e50;
        }
		.imagesrc{
			height: 70px;
			width:120px;
			margin-left: 0px;
		}
    </style>
</head>
<body>
    <div class="header">
		<img class = "imagesrc" src="../images/logo4.png" alt = "" />
        <h1>TechS Inc Leave Management System</h1>
		<div class="nav-links">
            <a href="../index.php">Home</a>
            <a href="../model/login.php">Login</a>
            <a href="../model/signup.php">Signup</a>
            <a href="../view/about.php">About</a>
        </div>
    </div>
    
    <div class="container">
        <h2>Login</h2>
        <form action="../model/login.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
    
    <div class="footer">
        © TechS Inc Company 2024, All Rights Reserved.
    </div>
</body>
</html>
<?php
include '../controller/db_connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($password == $user['password']) {
            session_start();
            $_SESSION['username'] = $username;
			$_SESSION['staff_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            if ($user['role'] == 'admin') {
                echo "<script>alert('Login Success [Admin Mode]'); window.location.href='../view/admin_dashboard.php';</script>";
            } else {
                echo "<script>alert('Login Success [Staff Mode]'); window.location.href='../view/Staff_dashboard.php';</script>";
            }
        } else {
            echo "<script>alert('Invalid password'); window.location.href='../model/login.php';</script>";
        }
    } else {
        echo "<script>alert('User not found'); window.location.href='../model/login.php';</script>";
    }
    $conn->close();
}
?>