<?php
include '../controller/db_connect.php'; 

session_start();

$staff_id = $_SESSION['staff_id'];

$sql_leave_info = "
    SELECT lt.leave_category, lt.available_leaves,
    IFNULL(SUM(DATEDIFF(la.end_date, la.start_date) + 1), 0) AS taken_leaves
    FROM leave_types lt
    LEFT JOIN leave_applications la
    ON lt.leave_category = la.leave_type 
    AND la.staff_id = '$staff_id' 
    AND la.status = 'Approved'
    GROUP BY lt.leave_category, lt.available_leaves

    UNION ALL

    SELECT 'Comp Off Leave' AS leave_category, 
           NULL AS available_leaves,
           IFNULL(SUM(number_of_days), 0) AS taken_leaves
    FROM comp_off_leave_applications
    WHERE staff_id = '$staff_id' and status = 'Approved'
";

$result_leave_info = $conn->query($sql_leave_info);

if (!$result_leave_info) {
    die("Query failed: " . $conn->error);
}

$leave_info = [];
while ($row = $result_leave_info->fetch_assoc()) {
    $leave_info[] = $row;
}

$sql_comp_off_total = "
    SELECT IFNULL(SUM(number_of_days), 0) AS total_comp_off
    FROM comp_off_leaves
    WHERE staff_id = '$staff_id'
";

$result_comp_off_total = $conn->query($sql_comp_off_total);

if (!$result_comp_off_total) {
    die("Query failed: " . $conn->error);
}

$comp_off_total = $result_comp_off_total->fetch_assoc();
$comp_off_total_days = $comp_off_total['total_comp_off'];

$comp_off_taken_leaves = 0;
foreach ($leave_info as $info) {
    if ($info['leave_category'] == 'Comp Off Leave') {
        $comp_off_taken_leaves = $info['taken_leaves'];
        break;
    }
}
$remaining_comp_off_leaves = $comp_off_total_days - $comp_off_taken_leaves;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Leave Management System</title>
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
        .leave-info {
            margin-bottom: 20px;
        }
        .leave-info h3 {
            margin-bottom: 10px;
        }
        .leave-info ul {
            list-style-type: none;
            padding: 0;
        }
        .leave-info ul li {
            margin-bottom: 5px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .leave-info ul li span {
            display: inline-block;
            width: 200px;
            font-weight: bold;
        }
        .leave-info ul li.taken-leaves-low {
            background-color: #dff0d8; /* Light green */
        }
        .leave-info ul li.taken-leaves-medium {
            background-color: #fcf8e3; /* Light yellow */
        }
        .leave-info ul li.taken-leaves-high {
            background-color: #f2dede; /* Light red */
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
            <span><a href="../view/staff_dashboard.php" style="color: white; text-decoration: none;">Home</a></span> | 
            <span><a href="../index.php" style="color: white; text-decoration: none;">Logout</a></span> | 
            <span>Hi staff</span>
        </div>
    </div>
    <div class="container">
        <div class="sidebar">
            <a href="../controller/apply_leave.php">Apply Leave</a>
        </div>
        <div class="content">
            <h2>Staff Dashboard</h2>
            <div class="leave-info">
                <h3>Leave Summary</h3>
                <ul>
                    <?php foreach ($leave_info as $info): ?>
                        <?php
                            $taken_percentage = ($info['available_leaves'] > 0) ? ($info['taken_leaves'] / $info['available_leaves']) * 100 : 0;
                            $class = '';
                            if ($info['leave_category'] == 'Comp Off Leave') {
                                $available_leaves = $remaining_comp_off_leaves;
                                $class = 'taken-leaves-low'; 
                            } else {
                                if ($taken_percentage < 50) {
                                    $class = 'taken-leaves-low';
                                } elseif ($taken_percentage >= 50 && $taken_percentage < 80) {
                                    $class = 'taken-leaves-medium';
                                } else {
                                    $class = 'taken-leaves-high';
                                }
                                $available_leaves = $info['available_leaves'] - $info['taken_leaves'];
                            }
                        ?>
                        <li class="<?php echo $class; ?>">
                            <span>Category: </span><?php echo $info['leave_category']; ?><br>
                            <span>Total Leaves: </span><?php echo ($info['leave_category'] == 'Comp Off Leave') ? $comp_off_total_days : $info['available_leaves']; ?><br>
                            <span>Leaves Taken: </span><?php echo $info['taken_leaves']; ?><br>
                            <span>Available Leaves: </span><?php echo $available_leaves ?? 'N/A'; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer">
        © TechS Inc Company 2024, All Rights Reserved.
    </div>
</body>
</html>
