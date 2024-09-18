<?php
include '../controller/db_connect.php';
session_start();

$staff_id = $_SESSION['staff_id'];

$records_per_page = 1;
$current_page_new_requests = isset($_GET['page_new']) ? (int)$_GET['page_new'] : 1;
$start_from_new_requests = ($current_page_new_requests - 1) * $records_per_page;

$sql_new_requests = "SELECT * FROM leave_applications 
                     WHERE staff_id = '$staff_id' 
                     AND start_date >= DATE_SUB(CURDATE(), INTERVAL 3 DAY) 
                     ORDER BY start_date DESC 
                     LIMIT $start_from_new_requests, $records_per_page";
$result_new_requests = $conn->query($sql_new_requests);
if (!$result_new_requests) {
    die("Error executing query for new leave requests: " . $conn->error);
}

$sql_count_new_requests = "SELECT COUNT(*) AS total FROM leave_applications 
                           WHERE staff_id = '$staff_id' 
                           AND start_date >= DATE_SUB(CURDATE(), INTERVAL 3 DAY)";
$result_count_new_requests = $conn->query($sql_count_new_requests);
if (!$result_count_new_requests) {
    die("Error executing query for count of new leave requests: " . $conn->error);
}
$total_records_new_requests = $result_count_new_requests->fetch_assoc()['total'];
$total_pages_new_requests = ceil($total_records_new_requests / $records_per_page);

$current_page_comp_off_requests = isset($_GET['page_comp_off']) ? (int)$_GET['page_comp_off'] : 1;
$start_from_comp_off_requests = ($current_page_comp_off_requests - 1) * $records_per_page;

$sql_comp_off_requests = "SELECT * FROM comp_off_leave_applications 
                          WHERE staff_id = '$staff_id' 
                          ORDER BY leave_date DESC 
                          LIMIT $start_from_comp_off_requests, $records_per_page";
$result_comp_off_requests = $conn->query($sql_comp_off_requests);
if (!$result_comp_off_requests) {
    die("Error executing query for comp-off leave requests: " . $conn->error);
}

$sql_count_comp_off_requests = "SELECT COUNT(*) AS total FROM comp_off_leave_applications 
                                WHERE staff_id = '$staff_id'";
$result_count_comp_off_requests = $conn->query($sql_count_comp_off_requests);
if (!$result_count_comp_off_requests) {
    die("Error executing query for count of comp-off leave requests: " . $conn->error);
}
$total_records_comp_off_requests = $result_count_comp_off_requests->fetch_assoc()['total'];
$total_pages_comp_off_requests = ceil($total_records_comp_off_requests / $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Status - Leave Management System</title>
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
        .leave-entry {
            margin-bottom: 20px;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .leave-entry .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .leave-entry .form-group label {
            flex: 0 0 120px;
            margin: 0;
        }
        .leave-entry .form-group input,
        .leave-entry .form-group textarea {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .leave-entry .status-pending {
            background-color: #ffcccc; /* Light red */
            color: #cc0000; /* Dark red */
            font-weight: bold;
        }
        .leave-entry .status-approved {
            background-color: #ccffcc; /* Light green */
            color: #009900; /* Dark green */
            font-weight: bold;
        }
        .leave-entry .status-rejected {
            background-color: #ffe6e6; /* Light pink */
            color: #990000; /* Dark pink */
            font-weight: bold;
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
        .imagesrc {
            height: 70px;
            width: 120px;
            margin-left: 0px;
        }
        .pagination {
            text-align: center;
            margin: 20px 0;
        }
        .pagination a {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        .pagination a.active {
            background-color: #34495e;
            color: white;
        }
        .pagination a:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <img class="imagesrc" src="../images/logo4.png" alt="" />
        <h1>TechS Inc Leave Management System</h1>
        <div class="user-info">
            <span><a href="../view/staff_dashboard.php" style="color: white; text-decoration: none;">Home</a></span> | 
            <span><a href="../index.php" style="color: white; text-decoration: none;">Logout</a></span> | 
            <span>Hi staff</span>
        </div>
    </div>
    
    <div class="container">
        <div class="sidebar">
            <a href="../view/staff_dashboard.php">Apply Leave</a>
            <a href="../view/leave_history.php">View Leave History</a>
            <a href="../view/leave_status.php">View Leave Status</a>
            <a href="../view/profile.php">View Profile</a>
        </div>
        
        <div class="content">
            <h2>New Leave Requests</h2>
            <?php
            if ($result_new_requests->num_rows > 0) {
                while($row = $result_new_requests->fetch_assoc()) {
                    $status_class = '';
                    if ($row["status"] == 'pending') {
                        $status_class = 'status-pending';
                    } elseif ($row["status"] == 'Approved') {
                        $status_class = 'status-approved';
                    } elseif ($row["status"] == 'Denied') {
                        $status_class = 'status-rejected';
                    }
                    echo "<div class='leave-entry'>";
                    echo "<div class='form-group'><label>Leave ID:</label><input type='text' value='" . $row["app_id"] . "' disabled></div>";
                    echo "<div class='form-group'><label>Leave Type:</label><input type='text' value='" . $row["leave_type"] . "' disabled></div>";
                    echo "<div class='form-group'><label>Start Date:</label><input type='text' value='" . $row["start_date"] . "' disabled></div>";
                    echo "<div class='form-group'><label>End Date:</label><input type='text' value='" . $row["end_date"] . "' disabled></div>";
                    echo "<div class='form-group'><label>Reason:</label><textarea disabled>" . $row["reason"] . "</textarea></div>";
                    echo "<div class='form-group $status_class'><label>Status:</label><input type='text' value='" . $row["status"] . "' disabled></div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No new leave applications found</p>";
            }
            ?>
            <div class="pagination">
                <?php
                for ($page = 1; $page <= $total_pages_new_requests; $page++) {
                    echo '<a href="../view/leave_status.php?page_new=' . $page . '"' . ($page == $current_page_new_requests ? ' class="active"' : '') . '>' . $page . '</a>';
                }
                ?>
            </div>

            <h2>Comp Off Leave Requests</h2>
            <?php
            if ($result_comp_off_requests->num_rows > 0) {
                while($row = $result_comp_off_requests->fetch_assoc()) {
                    $status_class = '';
                    if ($row["status"] == 'pending') {
                        $status_class = 'status-pending';
                    } elseif ($row["status"] == 'Approved') {
                        $status_class = 'status-approved';
                    } elseif ($row["status"] == 'Denied') {
                        $status_class = 'status-rejected';
                    }
                    echo "<div class='leave-entry'>";
                    echo "<div class='form-group'><label>Comp Off ID:</label><input type='text' value='" . $row["id"] . "' disabled></div>";
                    echo "<div class='form-group'><label>Staff ID:</label><input type='text' value='" . $row["staff_id"] . "' disabled></div>";
                    echo "<div class='form-group'><label>Number of Days:</label><input type='text' value='" . $row["number_of_days"] . "' disabled></div>";
                    echo "<div class='form-group'><label>Leave Date:</label><input type='text' value='" . $row["leave_date"] . "' disabled></div>";
                    echo "<div class='form-group $status_class'><label>Status:</label><input type='text' value='" . $row["status"] . "' disabled></div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No comp off leave applications found</p>";
            }
            ?>
            <div class="pagination">
                <?php
                for ($page = 1; $page <= $total_pages_comp_off_requests; $page++) {
                    echo '<a href="../view/leave_status.php?page_comp_off=' . $page . '"' . ($page == $current_page_comp_off_requests ? ' class="active"' : '') . '>' . $page . '</a>';
                }
                ?>
            </div>
            <?php $conn->close(); ?>
        </div>
    </div>
    
    <div class="footer">
        © TechS Inc Company 2024, All Rights Reserved.
    </div>
</body>
</html>
