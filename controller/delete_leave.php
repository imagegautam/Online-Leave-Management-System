<?php
include '../controller/db_connect.php';

$alert_message = "";
$modal_button = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $leave_id = $_POST['leave_id'];
    $confirm_deletion = isset($_POST['confirm_deletion']);

    if ($confirm_deletion) {
        $sql = "DELETE FROM leave_types WHERE leave_id = '$leave_id'";

        if ($conn->query($sql) === TRUE) {
			$alert_message = "Leave record deleted successfully.";
			$modal_button = "../controller/delete_leave.php";
        } else {
			$alert_message = "Error deleting leave record: ";
			$modal_button = "../controller/delete_leave.php";
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
    <title>Delete Leave - Leave Management System</title>
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
		<img class = "imagesrc" src="../images/logo4.png" alt = "" />
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
            <h2>Delete Leave</h2>
            <form action="../controller/delete_leave.php" method="post">
                <div class="form-group">
                    <label>Leave ID *</label>
                    <input type="text" name="leave_id" placeholder="Leave ID" required>
                </div>
                <div class="form-group">
                    <label>Confirm Deletion</label>
                    <input type="checkbox" name="confirm_deletion" required> I confirm that I want to delete this leave record
                </div>
                <button type="submit" class="btn">Delete Leave</button>
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
</body>
</html>


