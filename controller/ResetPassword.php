<?php

$alert_message = "";
$modal_button = "";

$token = $_GET["token"];

$token_hash = hash("sha256", $token);

require_once('../controller/db_connect.php');

$sql = "SELECT * FROM users
        WHERE reset_token_hash = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
            margin-left: auto;
            margin-right: auto;
        }
        .form-group {
            margin-bottom: 15px;
            position: relative;
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
        .form-group .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
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
        .imagesrc {
            height: 70px;
            width: 120px;
            margin-left: 0px;
        }
        .modal {
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            border-radius: 5px;
            text-align: center;
        }
        .modal-button {
            display: block;
            margin: 20px auto;
            width: 65px;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .modal-button:hover {
            background-color: #2980b9;
        }
		.form-group .toggle-password{
			padding-top: 25px;
		}
		input[type="checkbox" i] {
			height: 30px;
			padding-top: 20px;
			margin-top: 15px;
			width: 20px;
		}
    </style>
</head>
<body>
    <header>
    <div class="header">
        <img class="imagesrc" src="../images/logo4.png" alt="" />
        <h1>TechS Inc Leave Management System</h1>
        <div class="nav-links">
            <a href="../index.php">Home</a>
            <a href="../model/login.php">Login</a>
            <a href="../model/signup.php">Signup</a>
            <a href="../model/about.php">About</a>
        </div>
    </div>
    </header>
    <div class="container">
        <h1>Reset Password</h1>
        <div class="form-container">
            <div class="form-inner">
                <form action="ProcessResetPassword.php" method="POST" class="reset-password" autocomplete="off">
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                    <div class="form-group">
                        <label for="password"><b>Password:</b></label>
                        <div class="input-container user-input">
                            <input type="password" id="password" name="password" onblur="this.value=this.value.trim()" placeholder="Please enter your Password" required />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation"><b>Repeat password:</b></label>
                        <div class="input-container user-input">
                            <input type="password" id="password_confirmation" name="password_confirmation" onblur="checkPasswordMatch()" onkeyup="checkPasswordMatch()" placeholder="Please re-enter your Password" required />
                            <input type="checkbox" onclick="togglePasswordVisibility()">
                            <label for "">Show Password</label>
                            <span id="passwordMatchMessage"></span>
                        </div>
                    </div>
                        <button type="submit" class="btn">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="footer">
        © TechS Inc Company 2024, All Rights Reserved.
    </div>

    <script>
        function checkPasswordMatch() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("password_confirmation").value;
            var message = document.getElementById("passwordMatchMessage");

            if (password === confirmPassword) {
                message.innerHTML = "Passwords match";
                message.style.color = "green";
            } else {
                message.innerHTML = "Passwords do not match";
                message.style.color = "red";
            }
        }
    </script>
<script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById("password");
        var confirmPasswordInput = document.getElementById("password_confirmation");
        
        if (passwordInput.type === "password" && confirmPasswordInput.type === "password") {
            passwordInput.type = "text";
            confirmPasswordInput.type = "text";
        } else {
            passwordInput.type = "password";
            confirmPasswordInput.type = "password";
        }
    }
</script>

</body>
</html>
