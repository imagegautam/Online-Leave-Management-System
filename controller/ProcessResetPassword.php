<?php
require_once('../controller/db_connect.php');

$token = $_POST["token"];
$password = $_POST["password"];
$token_hash = hash("sha256", $token);

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

define('ENCRYPTION_METHOD', 'AES-256-CBC');
define('SECRET_KEY', 'your_secret_key');  // Make sure to keep this consistent with your signup process
define('SECRET_IV', 'your_secret_iv');

function encryptPassword($password) {
    $key = hash('sha256', SECRET_KEY);
    $iv = substr(hash('sha256', SECRET_IV), 0, 16);
    return openssl_encrypt($password, ENCRYPTION_METHOD, $key, 0, $iv);
}

$alert_message = "";
$modal_button = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];  // New password input from the user
    $user_id = $user["user_id"];  // Assuming $user["user_id"] is available
    $encrypted_password = encryptPassword($password);  // Encrypt the password

    // Update the encrypted password in the database
    $sql = "UPDATE users SET password = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $encrypted_password, $user_id);  // Use the encrypted password
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        $alert_message = "Password updated successfully!";
        $modal_button = "../model/login.php";  // Redirect to login page
    } else {
        $alert_message = "Failed to update password!";
        $modal_button = "../model/reset_password.php";  // Redirect back to reset password page
    }
    

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        /* Same CSS from your signup page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
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

<?php if ($alert_message != ""): ?>
<div class="modal">
    <div class="modal-content">
        <p><?php echo $alert_message; ?></p>
        <a href="<?php echo $modal_button; ?>" class="modal-button">Proceed</a>
    </div>
</div>
<?php endif; ?>

</body>
</html>