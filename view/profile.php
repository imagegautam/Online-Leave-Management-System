<?php
session_start();
include '../controller/db_connect.php';

if (!isset($_SESSION['staff_id'])) {
    header("Location: index.php"); 
    exit;
}

$staff_id = $_SESSION['staff_id'];

$sql = "SELECT * FROM users WHERE user_id = '$staff_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $staff = $result->fetch_assoc();
} else {
    echo "<script>alert('No staff found with the given ID.');window.location.href='../index.php';</script>";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile - Leave Management System</title>
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
            padding: 2px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-image: url('../images/sidebar.jpg');
        }
        .header h1 {
            margin: 0;
        }
        .user-info {
            font-size: 14px;
			margin-right: 10px;
        }
        .container {
            display: flex;
            margin: 20px;
            width: 1200px;
        }
        .sidebar {
            width: 200px;
            background-color: #2c3e50;
            padding: 20px;
            background-image: url('../images/sidebar.jpg');
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 10px;
            margin-bottom: 5px;
        }
        .sidebar a:hover {
            background-color: #465669;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            background-color: white;
            margin-left: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group span {
            display: block;
            padding: 8px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
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
        .profile-header {
            display: flex;
            align-items: center;
        }
        .profile-header img {
            width: 100px;
            height: 100px;
            margin-right: 10px;
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
        <div class="user-info">
            <span><a href="../view/staff_dashboard.php" style="color: white; text-decoration: none;">Home</a></span> | 
            <span><a href="../index.php" style="color: white; text-decoration: none;">Logout</a></span> | 
            <span>Hi <?php echo htmlspecialchars($staff['username']); ?></span>
        </div>
    </div>
    <div class="container">
        <div class="sidebar">
            <a href="../controller/apply_leave.php">Apply Leave</a>
            <a href="../view/leave_history.php">View Leave History</a>
            <a href="../view/leave_status.php">View Leave Status</a>
            <a href="../view/profile.php">View Profile</a>
        </div>
        <div class="content">
            <div class="profile-header">
                <img src="../images/staff.jpg" alt="Staff Logo">
                <h2>Profile Details</h2>
            </div>
            <div class="form-group">
                <label>Staff ID</label>
                <span><?php echo htmlspecialchars($staff['user_id']); ?></span>
            </div>
            <div class="form-group">
                <label>Name</label>
                <span><?php echo htmlspecialchars($staff['username']); ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <span><?php echo htmlspecialchars($staff['password']); ?></span>
            </div>
            <div class="form-group">
                <label>Role</label>
                <span><?php echo htmlspecialchars($staff['role']); ?></span>
            </div>
        </div>
    </div>
    <div class="footer">
        © TechS Inc Company 2024, All Rights Reserved.
    </div>
</body>
</html>
