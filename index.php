<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechS Inc Leave Management System</title>
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
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
			background-image: url('images/sidebar.jpg');
        }
        .container {
            margin: 13px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
			width: 600px;
			margin-left: 400px;
			padding-top: 50px;
			padding-left: 50px;
			padding-right: 50px;
			padding-bottom: 50px;
			
        }
        .btn {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
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
			background-image: url('images/sidebar.jpg');
        }
		.header {
            background-color: #34495e;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
        }
        .nav-links {
            display: flex;
            gap: 20px;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .nav-links a:hover {
            background-color: #2c3e50;
        }
		.imagesrc1{
			height: 300px;
			width:600px;
			margin-left: 0px;
		}
    </style>
</head>
<body>
    <div class="header">
        <h1>TechS Inc Leave Management System</h1>
		<div class="nav-links">
            <a href="index.php">Home</a>
            <a href="model/login.php">Login</a>
            <a href="model/signup.php">Signup</a>
            <a href="view/about.php">About</a>
        </div>
    </div>
    
    <div class="container">
	<img class = "imagesrc1" src="images/logo4.png" alt = "" />
        <h2>Welcome to the TechS Inc Online Leave Management System</h2>
        <p>Your one-stop solution for managing leave requests efficiently.</p>
        <a href="model/login.php" class="btn">Login</a>
    </div>
    
    <div class="footer">
        © TechS Inc Company 2024, All Rights Reserved.
    </div>
</body>
</html>