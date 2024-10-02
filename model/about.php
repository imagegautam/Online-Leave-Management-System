<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Leave Management System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
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
            font-size: 24px;
        }
        .header .user-info {
            font-size: 14px;
			margin-right: 10px;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .container img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .content {
            padding: 20px;
        }
        .content h2 {
            color: #007bff;
            margin-top: 0;
        }
        .content p {
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .content h3 {
            color: #343a40;
        }
        .content ul {
            list-style: none;
            padding: 0;
        }
        .content ul li {
            background: url('../images/check.png') no-repeat left center;
            padding-left: 30px;
            margin-bottom: 10px;
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
            <span><a href="../index.php" style="color: white; text-decoration: none;">Home</a></span>  
        </div>
    </div>
    <div class="container">
        <div class="content">
            <h2>About Us</h2>
            <img src="../images/about.jpeg" alt="About Us">
            <p>Welcome to TechS Inc, a leading provider of innovative solutions in the tech industry. Our Leave Management System is designed to simplify and streamline the leave application process for both employees and administrators. We are committed to providing efficient and user-friendly software that enhances productivity and organizational management.</p>
            <h3>Our Mission</h3>
            <p>Our mission is to deliver top-notch software solutions that meet the evolving needs of our clients. We aim to empower businesses with the tools they need to manage their operations effectively and efficiently.</p>
            <h3>Our Vision</h3>
            <p>We envision a future where technology seamlessly integrates with everyday business processes, driving innovation and success. We strive to be at the forefront of this transformation, providing cutting-edge solutions that set the standard in the industry.</p>
            <h3>Why Choose Us</h3>
            <ul>
                <li>Expert team of professionals</li>
                <li>Commitment to quality and innovation</li>
                <li>Customer-centric approach</li>
                <li>Proven track record of success</li>
                <li>Comprehensive support and services</li>
            </ul>
        </div>
    </div>
    <div class="footer">
        © TechS Inc Company 2024, All Rights Reserved.
    </div>
</body>
</html>
