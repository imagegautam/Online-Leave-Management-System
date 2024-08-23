<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Leave Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .header {
            background-color: #8FBC8F;
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
			margin-right: 10px;
            font-size: 14px;
        }
        .container {
            display: flex;
            margin: 20px;
			width: 1200px;
        }
        .sidebar {
            width: 200px;
            background-color: #8FBC8F;
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
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            background-color: #FF8C00;
            color: white;
            padding: 10px 40px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #8FBC8F;
        }
        .footer {
            background-color: #8FBC8F;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
			background-image: url('../images/sidebar.jpg');
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
            <span><a href="../view/admin_dashboard.php" style="color: white; text-decoration: none;">Home</a></span> | 
			<span><a href="../index.php" style="color: white; text-decoration: none;">Logout</a></span> | 
            <span>Hi admin</span>
        </div>
    </div>
    
    <div class="container">
        <div class="sidebar">
            <a href="../view/admin_dashboard.php">Add Staff</a>
			<a href="../view/leave_requests.php">View Leave Requests</a>
			<a href="../view/view_all_staff.php">View All Staff</a>
            <a href="../view/add_leave.php">Add Leave Type</a>
			
        </div>
        
        <div class="content">
            <h2>Add Staff</h2>
            <form action="../view/admin_dashboard.php" method="post">
                <div class="form-group">
                    <label for="username">Name *</label>
                    <input type="text" placeholder="Staff Name" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" placeholder="Create Password" name="password" required>
                </div>
				<div class="form-group">
                <label for="role">Role:</label>
                <select id="role" name="role">
                    <option value="staff">Staff</option>
					<option value="admin">Admin</option>
                </select>
            </div>
                <button type="submit" class="btn">Add</button>
            </form>
        </div>
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
    $role = $_POST['role'];
    $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Staff Details Added successfully'); window.location.href='../view/admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Incorrect Details !!!'); window.location.href='../view/admin_dashboard.php';</script>";
    }
    $conn->close();
}
?>