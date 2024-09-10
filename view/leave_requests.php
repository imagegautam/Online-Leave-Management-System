<?php
include '../controller/db_connect.php'; 

$sql_pending_requests = "SELECT * FROM leave_applications WHERE status = 'pending'";
$result_pending_requests = $conn->query($sql_pending_requests);

$sql_pending_comp_off_requests = "SELECT * FROM comp_off_leave_applications WHERE status = 'pending'";
$result_pending_comp_off_requests = $conn->query($sql_pending_comp_off_requests);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Leave Requests - Leave Management System</title>
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
        .leave-request {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            background-color: #fff;
        }
        .leave-request h3 {
            margin: 0;
        }
        .leave-request p {
            margin: 5px 0;
        }
        .leave-request button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        .approve-btn {
            background-color: #3498db;
            color: white;
        }
        .approve-btn:hover {
            background-color: #2980b9;
        }
        .deny-btn {
            background-color: #e74c3c;
            color: white;
        }
        .deny-btn:hover {
            background-color: #c0392b;
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
    </style>
</head>
<body>
    <div class="header">
        <img class="imagesrc" src="../images/logo4.png" alt="Logo">
        <h1>TechS Inc Leave Management System</h1>
        <div class="user-info">
            <span><a href="../view/admin_dashboard.php" style="color: white; text-decoration: none;">Home</a></span> | 
            <span><a href="../index.php" style="color: white; text-decoration: none;">Logout</a></span> | 
            <span>Hi admin</span>
        </div>
    </div>
    <div class="container">
        <div class="sidebar">
            <a href="../controller/admin_dashboard.php">Add Staff</a>
            <a href="../view/leave_requests.php">View Leave Requests</a>
            <a href="../view/view_all_staff.php">View All Staff</a>
            <a href="../controller/add_leave.php">Add Leave Type</a>
            <a href="../controller/delete_leave.php">Delete Leave Type</a>
            <a href="../controller/add_compoff_leave.php">Add Comp-Off Leave</a>
        </div>
        <div class="content">
            <h2>Manage Leave Requests</h2>
            <?php if ($result_pending_requests->num_rows > 0 || $result_pending_comp_off_requests->num_rows > 0): ?>
                <?php while ($row = $result_pending_requests->fetch_assoc()): ?>
                    <div class="leave-request">
                        <h3>Leave Request ID: <?php echo $row['app_id']; ?></h3>
                        <p><strong>Staff ID:</strong> <?php echo $row['staff_id']; ?></p>
                        <p><strong>Leave Type:</strong> <?php echo $row['leave_type']; ?></p>
                        <p><strong>Start Date:</strong> <?php echo $row['start_date']; ?></p>
                        <p><strong>End Date:</strong> <?php echo $row['end_date']; ?></p>
                        <p><strong>Reason:</strong> <?php echo $row['reason']; ?></p>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="leave_id" value="<?php echo $row['app_id']; ?>">
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="approve-btn">Approve</button>
                        </form>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="leave_id" value="<?php echo $row['app_id']; ?>">
                            <input type="hidden" name="action" value="deny">
                            <button type="submit" class="deny-btn">Deny</button>
                        </form>
                    </div>
                <?php endwhile; ?>
                <?php while ($row = $result_pending_comp_off_requests->fetch_assoc()): ?>
                    <div class="leave-request">
                        <h3>Comp Off Leave Request ID: <?php echo $row['id']; ?></h3>
                        <p><strong>Staff ID:</strong> <?php echo $row['staff_id']; ?></p>
                        <p><strong>Date Worked:</strong> <?php echo $row['leave_date']; ?></p>
                        <p><strong>Number of Days:</strong> <?php echo $row['number_of_days']; ?></p>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="leave_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="leave_type" value="comp_off">
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" class="approve-btn">Approve</button>
                        </form>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="leave_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="leave_type" value="comp_off">
                            <input type="hidden" name="action" value="deny">
                            <button type="submit" class="deny-btn">Deny</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No pending leave requests.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="footer">
        © TechS Inc Company 2024, All Rights Reserved.
    </div>
</body>
</html>
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $leave_id = $_POST['leave_id'];
    $leave_type = isset($_POST['leave_type']) ? $_POST['leave_type'] : null;
    $action = $_POST['action'];

    if ($leave_type == 'comp_off') {
        $status = ($action == 'approve') ? 'Approved' : 'Denied';
        $sql_update_status = "UPDATE comp_off_leave_applications SET status = '$status' WHERE id = '$leave_id'";
    } else {
        $status = ($action == 'approve') ? 'Approved' : 'Denied';
        $sql_update_status = "UPDATE leave_applications SET status = '$status' WHERE app_id = '$leave_id'";
    }

    if ($conn->query($sql_update_status) === TRUE) {
        echo "<script>alert('Leave request status updated successfully.'); window.location.href='../view/leave_requests.php';</script>";
    } else {
        echo "<script>alert('Error updating leave request status: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>
