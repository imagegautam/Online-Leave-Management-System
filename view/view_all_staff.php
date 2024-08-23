<?php
include '../controller/db_connect.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 6;
$offset = ($page - 1) * $records_per_page;

$total_sql = "SELECT COUNT(*) FROM users WHERE role = 'staff'";
$total_result = $conn->query($total_sql);
$total_rows = $total_result->fetch_row()[0];
$total_pages = ceil($total_rows / $records_per_page);

$sql = "SELECT * FROM users WHERE role = 'staff' LIMIT $records_per_page OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Staff - Leave Management System</title>
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
            <h2>All Staff Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>Staff ID</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["user_id"] . "</td>";
                            echo "<td>" . $row["username"] . "</td>";
                            echo "<td>" . $row["password"] . "</td>";
                            echo "<td>
                                    <button class='action-btn update-btn' onclick='updateStaff(" . $row["user_id"] . ")'>Update</button>
                                    <button class='action-btn delete-btn' onclick='deleteStaff(" . $row["user_id"] . ")'>Delete</button>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No staff found</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
            
            <div class="pagination">
                <?php
                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<a href='../view/view_all_staff.php?page=$i'" . ($i == $page ? " class='active'" : "") . ">$i</a>";
                }
                ?>
            </div>
        </div>
    </div>
    
    <div class="footer">
        © TechS Inc Company 2024, All Rights Reserved.
    </div>
    
    <script>
        function updateStaff(id) {
            window.location.href = '../view/update_staff.php?id=' + id;
        }

        function deleteStaff(id) {
            if (confirm('Are you sure you want to delete this staff member?')) {
                window.location.href = '../view/delete_staff.php?id=' + id;
            }
        }
    </script>
</body>
</html>
