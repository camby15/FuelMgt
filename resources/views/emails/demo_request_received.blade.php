<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Demo Request</title>
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
        .divider {
            height: 2px;
            background-color: #069a9a;
            margin: 20px 0;
        }
        .details {
            margin-bottom: 20px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: 600;
            color: #033c42;
            width: 120px;
        }
        .detail-value {
            color: #555;
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
            <h1>New Demo Request Received</h1>
        </div>

        <div class="divider"></div>

        <div class="details">
            <div class="detail-row">
                <div class="detail-label">Name:</div>
                <div class="detail-value">{{ $data['name'] }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Email:</div>
                <div class="detail-value">{{ $data['email'] }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Company:</div>
                <div class="detail-value">{{ $data['company'] ?? 'Not provided' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Phone:</div>
                <div class="detail-value">{{ $data['phone'] }}</div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="footer">
            <p>This request was submitted through the website demo form.</p>
            <p>Please contact the prospect within 24 hours.</p>
        </div>
    </div>
</body>
</html>