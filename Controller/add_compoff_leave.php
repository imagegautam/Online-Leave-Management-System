<?php
include '../controller/db_connect.php';

$alert_message = "";
$modal_button = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $staff_id = $_POST['staff_id'];
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    $number_of_days = $_POST['number_of_days'];

    $sql = "INSERT INTO comp_off_leaves (staff_id, date_from, date_to, number_of_days) VALUES ('$staff_id', '$date_from', '$date_to', '$number_of_days')";

    if ($conn->query($sql) === TRUE) {
        $alert_message = "Comp-Off Leave added successfully.";
        $modal_button = "../controller/add_compoff_leave.php";
    } else {
        $alert_message = "Failed to add comp off Leave.";
        $modal_button = "../controller/add_compoff_leave.php";
    }

    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Comp-Off Leave - Leave Management System</title>
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
		.imagesrc{
			height: 70px;
			width:120px;
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
        <img class="imagesrc" src="../images/logo4.png" alt="" />
        <h1>TechS Inc Leave Management System</h1>
        <div class="user-info">
            <span><a href="../controller/admin_dashboard.php" style="color: white; text-decoration: none;">Home</a></span> | 
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
            <h2>Add Comp-Off Leave</h2>
            <form action="../controller/add_compoff_leave.php" method="post">
                <div class="form-group">
                    <label>Staff ID *</label>
                    <input type="text" name="staff_id" placeholder="Staff ID" required>
                </div>
                <div class="form-group">
                    <label>From Date *</label>
                    <input type="date" id="date_from" name="date_from" required>
                </div>
                <div class="form-group">
                    <label>To Date *</label>
                    <input type="date" id="date_to" name="date_to" required>
                </div>
                <div class="form-group">
                    <label>Number of Days *</label>
                    <input type="number" id="number_of_days" name="number_of_days" placeholder="Number of Days" readonly>
                </div>
                <button type="submit" class="btn">Add Comp-Off Leave</button>
            </form>
        </div>
    </div>
    
    <div class="footer">
        © TechS Inc Company 2024, All Rights Reserved.
    </div>

    <?php if ($alert_message != ""): ?>
        <div class="modal">
            <div class="modal-content">
                <p><?php echo $alert_message; ?></p>
                <a href="<?php echo $modal_button; ?>" class="modal-button">Proceed</a>
            </div>
        </div>
    <?php endif; ?>

    <script>
        const dateFrom = document.getElementById('date_from');
        const dateTo = document.getElementById('date_to');
        const numberOfDays = document.getElementById('number_of_days');

        dateFrom.addEventListener('change', calculateDays);
        dateTo.addEventListener('change', calculateDays);

        function calculateDays() {
            const fromDate = new Date(dateFrom.value);
            const toDate = new Date(dateTo.value);

            if (fromDate && toDate && toDate >= fromDate) {
                const timeDiff = Math.abs(toDate - fromDate);
                const daysDiff = Math.ceil(timeDiff / (1000 * 60 * 60 * 24)) + 1;
                numberOfDays.value = daysDiff;
            } else {
                numberOfDays.value = '';
            }
        }
    </script>
</body>
</html>

