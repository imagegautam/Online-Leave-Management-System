<?php
include '../controller/db_connect.php';

define('ENCRYPTION_METHOD', 'AES-256-CBC');
define('SECRET_KEY', 'your_secret_key');
define('SECRET_IV', 'your_secret_iv');

$alert_message = "";
$modal_Message = "";
$modal_button = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];

    $key = hash('sha256', SECRET_KEY);
    $iv = substr(hash('sha256', SECRET_IV), 0, 16);
    $encrypted_password = openssl_encrypt($new_password, ENCRYPTION_METHOD, $key, 0, $iv);

    $sql = "UPDATE users SET password=? WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $encrypted_password, $username);

    if ($stmt->execute()) {
        $alert_message = "Password Reset Success";
        $modal_Message = "Your password has been successfully reset.";
        $modal_button = "../model/login.php";
    } else {
        $alert_message = "Password Reset Failed";
        $modal_Message = "An error occurred while resetting your password. Please try again.";
        $modal_button = "../controller/reset_password.php";
    }

    $stmt->close();
    $conn->close();
}

$username = isset($_GET['username']) ? $_GET['username'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Leave Management System</title>
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
    </style>
</head>
<body>
    <div class="header">
        <img class="imagesrc" src="../images/logo4.png" alt="" />
        <h1>TechS Inc Leave Management System</h1>
        <div class="nav-links">
            <a href="../index.php">Home</a>
            <a href="../model/about.php">About</a>
        </div>
    </div>

    <div class="container">
        <h2>Reset Password</h2>
        <form action="../controller/reset_password.php" method="post">
            <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
            <div class="form-group">
                <label for="new_password">Enter New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <button type="submit" class="btn">Reset Password</button>
        </form>
    </div>

    <div class="footer">
        © TechS Inc Company 2024, All Rights Reserved.
    </div>

    <?php if ($alert_message != ""): ?>
    <div class="modal">
        <div class="modal-content">
            <h2><?php echo $alert_message; ?></h2>
            <p><?php echo $modal_Message; ?></p>
            <a href="<?php echo $modal_button; ?>" class="modal-button">Proceed</a>
        </div>
    </div>
    <?php endif; ?>
</body>
</html>
