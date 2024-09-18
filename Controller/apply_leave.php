<?php
include '../controller/db_connect.php';

$alert_message = "";
$modal_button = "";

session_start();

if (!isset($_SESSION['staff_id'])) {
    header("Location: ../index.php"); 
    exit;
}

$staff_id = $_SESSION['staff_id'];

$sql = "SELECT * FROM users WHERE user_id = '$staff_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $staff = $result->fetch_assoc();
} else {
	$alert_message = "No staff found with the given ID.";
    $modal_button = "../view/staff_dashboard.php";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $staff_id = $_POST['staff_id'];
    $leave_type = $_POST['leave_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    function calculateDays($start_date, $end_date) {
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = $end->diff($start);
        return $interval->days + 1;
    }

    if ($leave_type == 'Comp Off Leave') {
        $sql_check_comp_off = "SELECT * FROM comp_off_leaves WHERE staff_id = '$staff_id'";
        $result_check_comp_off = $conn->query($sql_check_comp_off);

        if ($result_check_comp_off->num_rows > 0) {
            $number_of_days = calculateDays($start_date, $end_date);
            $sql = "INSERT INTO comp_off_leave_applications (staff_id, leave_date, number_of_days, status) 
                    VALUES ('$staff_id', '$start_date', '$number_of_days', 'Pending')";

            if ($conn->query($sql) === TRUE) {
				$alert_message = "Comp Off Leave application submitted successfully.";
				$modal_button = "../controller/apply_leave.php";
			} else {
				$alert_message = "Error submitting Comp Off Leave application.";
				$modal_button = "../controller/apply_leave.php";
			}
        } else {
            $alert_message = "No existing Comp Off Leave records found. Please record a comp off before applying.";
			$modal_button = "../view/staff_dashboard.php";
			exit;
        }
    } else {
        $sql_leave_type = "SELECT leave_id, available_leaves FROM leave_types WHERE leave_category = '$leave_type'";
        $result_leave_type = $conn->query($sql_leave_type);

        if ($result_leave_type === FALSE) {
            die("Error in SQL query: " . $conn->error);
        }

        $leave_type_info = $result_leave_type->fetch_assoc();

        if ($leave_type_info) {
            $leave_type_id = $leave_type_info['leave_id'];
            $available_leaves = $leave_type_info['available_leaves'];

            $sql_taken_leaves = "SELECT SUM(DATEDIFF(end_date, start_date) + 1) AS total_taken_leaves FROM leave_applications join leave_types as lp on leave_applications.leave_type_id = lp.leave_id WHERE staff_id = '$staff_id' AND leave_applications.leave_type_id = '$leave_type_id' AND status = 'Approved'";
            $result_taken_leaves = $conn->query($sql_taken_leaves);
            $taken_leaves_info = $result_taken_leaves->fetch_assoc();
            $total_taken_leaves = $taken_leaves_info['total_taken_leaves'];

            $new_leave_days = calculateDays($start_date, $end_date);
            
            $pending_leaves = $available_leaves - $total_taken_leaves;

            if ($leave_type == 'Medical Leave') {
                $status = ($pending_leaves >= $new_leave_days) ? 'Approved' : 'Pending';
            } else {
                $status = ($pending_leaves >= 5 && $pending_leaves - $new_leave_days >= 5) ? 'Approved' : 'Pending';
            }

            $sql = "INSERT INTO leave_applications (staff_id, leave_type, start_date, end_date, reason, status, leave_type_id) 
                    VALUES ('$staff_id', '$leave_type', '$start_date', '$end_date', '$reason', '$status', '$leave_type_id')";

            if ($conn->query($sql) === TRUE) {
                $alert_message = "Leave application submitted successfully.";
				$modal_button = "../controller/apply_leave.php";
            } else {
                $alert_message = "Error submitting leave application";
				$modal_button = "../controller/apply_leave.php";
            }
        } else {
			$alert_message = "Invalid leave type selected.";
			$modal_button = "../controller/apply_leave.php";
        }
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply Leave - Leave Management System</title>
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
			margin-right:10px;
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
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            background-color: #FF8C00;
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
            padding-left: 0px;
			border-left-width: 0px;
			padding-right: 10px;
			padding-top: 10px;
			padding-bottom: 10px;
            border: 1px solid #888;
            width: 30%;
            border-radius: 5px;
            text-align: center;
        }
        .modal-button {
            display: block;
            margin: 20px auto;
			width: 100px;
			height: 20px;
            padding: 10px 20px;
			font: status-bar;
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
        <img class="imagesrc" src="../images/logo4.png" alt="Logo">
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
            <h2>Apply Leave</h2>
            <form action="../controller/apply_leave.php" method="post">
                <div class="form-group">
                    <label>Staff ID *</label>
					<input type="text" name="staff_id" value="<?php echo $staff_id; ?>" readonly>                </div>
                <div class="form-group">
                    <label>Leave Type *</label>
                    <select name="leave_type" required>
                        <option value="">Select Leave Type</option>
                        <option value="Casual Leave">Casual Leave</option>
                        <option value="Medical Leave">Medical Leave</option>
                        <option value="Comp Off Leave">Comp Off Leave</option>
                        <option value="Sick Leave">Sick Leave</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Start Date *</label>
                    <input type="date" name="start_date" required>
                </div>
                <div class="form-group">
                    <label>End Date *</label>
                    <input type="date" name="end_date" required>
                </div>
                <div class="form-group">
                    <label>Reason</label>
                    <textarea name="reason" placeholder="Reason for leave"></textarea>
                </div>
                <button type="submit" class="btn">Apply Leave</button>
            </form>
        </div>
    </div>
    <div class="footer">
        © TechS Inc Company 2024, All Rights Reserved.
    </div>
</body>
</html>
<?php if ($alert_message != ""): ?>
    <div class="modal">
        <div class="modal-content">
            <p><?php echo $alert_message; ?></p>
            <a href="<?php echo $modal_button; ?>" class="modal-button">Proceed</a>
        </div>
    </div>
    <?php endif; ?>
