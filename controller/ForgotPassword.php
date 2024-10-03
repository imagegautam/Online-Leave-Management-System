<?php

$alert_message = "";
$modal_button = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST["email"];
    $token = bin2hex(random_bytes(16));
    $token_hash = hash("sha256", $token);
    $expiry = date("Y-m-d H:i:s", time() + 60 * 30);
    require_once '../controller/db_connect.php';

    // Check if the email exists first
    $check_sql = "SELECT email FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Email exists, proceed with updating the reset token
        $sql = "UPDATE users
                SET reset_token_hash = ?,
                    reset_token_expires_at = ?
                WHERE email = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $token_hash, $expiry, $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $mail = require __DIR__ . "/mailer.php";
            $mail->setFrom("usermaltego2@gmail.com");
            $mail->addAddress($email);
            $mail->Subject = "Reset your account password";
            $mail->Body = <<<END

            Click <a href="http://localhost/Leave/controller/ResetPassword.php?token=$token">here</a> 
            to reset your password.

END;

            try {
                $mail->send();
                $alert_message = "Message sent successfully! Please check your Gmail inbox.";
                $modal_button = "../controller/ForgotPassword.php";
            } catch (Exception $e) {
                $alert_message = "Message could not be sent. Incorrect Mail...";
                $modal_button = "../controller/ForgotPassword.php";
            }
        } else {
            $alert_message = "Error updating the token. Please try again.";
            $modal_button = "../controller/ForgotPassword.php";
        }
    } else {
        // Email does not exist
        $alert_message = "No account associated with this email.";
        $modal_button = "../controller/ForgotPassword.php";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Forgot Password</title>
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
            margin-bottom: 20px;
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
    <div class="container">
            <h1>Forgot Password</h1>
            <div class="form-container">
                <div class="form-inner">
                    <form action="ForgotPassword.php" method="POST" class="forgot-password" autocomplete="off">
                    <div class="form-group">
                            <label for="email"><b>Email Address:</b></label>
                                <input type="email" id="email" name="email" onblur="this.value=this.value.trim()" placeholder="Please enter your Email Address" required />
                        </div>
                        <button type="submit" class="btn">Send Code</button>
                        <div class="login-link">
                            <a href=" ../model/login.php">Back to Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="footer">
        © TechS Inc Company 2024, All Rights Reserved.
    </div>
    <?php if ($alert_message != ""): ?>
    <div class="modal">
        <div class="modal-content">
            <h2><?php echo $alert_message; ?></h2>
            <a href="<?php echo $modal_button; ?>" class="modal-button">Proceed</a>
        </div>
    </div>
    <?php endif; ?>
    </body>
</html>
