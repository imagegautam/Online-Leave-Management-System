<?php
include '../controller/db_connect.php';
session_start();

$staff_id = $_SESSION['staff_id'];

$sql = "SELECT app_id, staff_id, us.username, leave_type, start_date, end_date, reason, status
				FROM leave_applications join users as us on leave_applications.staff_id = us.user_id WHERE staff_id = '$staff_id' and status = 'Approved'";
$result = $conn->query($sql);

$sqls = "SELECT id, staff_id, us.username, leave_date, number_of_days,status
				FROM comp_off_leave_applications join users as us on comp_off_leave_applications.staff_id = us.user_id WHERE staff_id = '$staff_id' and status = 'Approved'";
$results = $conn->query($sqls);
?>




</div>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Leave History - Leave Management System</title>
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
		table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #DCDCDC;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .action-btn {
            padding: 5px 10px;
            margin: 2px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .update-btn {
            background-color: #3498db;
            color: white;
        }
        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }
        .pagination {
            margin-top: 20px;
        }
        .pagination a {
            padding: 8px 16px;
            text-decoration: none;
            color: #3498db;
            border: 1px solid #ddd;
            margin: 0 4px;
            border-radius: 4px;
        }
        .pagination a:hover {
            background-color: #ddd;
        }
        .pagination a.active {
            background-color: #3498db;
            color: white;
            border: 1px solid #3498db;
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
            <span>Hi staff</span>
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
    <h2>Leave History</h2>
    <table>
        <thead>
            <tr>
                <th>Leave ID</th>
				<th>Staff ID</th>
				<th>Staff Name</th>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["app_id"] . "</td>";
					echo "<td>" . $row["staff_id"] . "</td>";
					echo "<td>" . $row["username"] . "</td>";
                    echo "<td>" . $row["leave_type"] . "</td>";
                    echo "<td>" . $row["start_date"] . "</td>";
                    echo "<td>" . $row["end_date"] . "</td>";
                    echo "<td>" . $row["reason"] . "</td>";
                    echo "<td>" . $row["status"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No leave history found</td></tr>";
            }
            ?>
        </tbody>
    </table>
	<h2>Comp off Leave History</h2>
    <table>
        <thead>
            <tr>
                <th>Comp off Leave ID</th>
				<th>Staff ID</th>
				<th>Staff Name</th>
                <th>Leave date</th>
                <th>Number of days</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($results->num_rows > 0) {
                while($row = $results->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
					echo "<td>" . $row["staff_id"] . "</td>";
					echo "<td>" . $row["username"] . "</td>";
                    echo "<td>" . $row["leave_date"] . "</td>";
                    echo "<td>" . $row["number_of_days"] . "</td>";
                    echo "<td>" . $row["status"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No leave history found</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
        </div>
    </div>
    
    <div class="footer">
        © TechS Inc Company 2024, All Rights Reserved.
    </div>
    
</body>
</html>