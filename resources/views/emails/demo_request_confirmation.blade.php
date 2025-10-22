<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Request Confirmation</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-color: #f9f9f9;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            width: 150px;
            margin: 0 auto 20px;
        }
        h1 {
            color: #033c42;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .content {
            margin-bottom: 30px;
            line-height: 1.6;
            color: #555;
        }
        .divider {
            height: 2px;
            background-color: #069a9a;
            margin: 20px 0;
        }
        .contact-info {
            margin-top: 20px;
        }
        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .contact-item i {
            margin-right: 10px;
            color: #069a9a;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #888;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="https://i.imgur.com/23Q8lZE.png" alt="Company Logo" style="max-width:100%;">
            </div>
            <h1>Thank You for Your Demo Request</h1>
        </div>

        <div class="content">
            <p>Dear {{ $data['name'] }},</p>
            <p>We've received your request for a demo and our team will contact you shortly to schedule a convenient time.</p>
            <p>In the meantime, feel free to explore our website or contact us directly if you have any immediate questions.</p>
        </div>

        <div class="divider"></div>

        <div class="contact-info">
            <p><strong>Our Contact Information:</strong></p>
            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <span>info@shrinqghana.com</span>
            </div>
            <div class="contact-item">
                <i class="fas fa-phone"></i>
                <span>+233 24 011 2912 / +233 54 108 1200</span>
            </div>
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>