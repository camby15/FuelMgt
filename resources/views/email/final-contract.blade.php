<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contract Activated</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            padding: 40px 0;
            color: #333;
        }
        .wrapper {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        .header {
            padding: 30px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }
        .logo {
            width: 80px;
            margin-bottom: 10px;
        }
        .content {
            padding: 30px;
        }
        h1 {
            font-size: 24px;
            color: #1a202c;
            margin: 0 0 15px;
        }
        p {
            line-height: 1.6;
            margin: 16px 0;
            font-size: 15px;
            color: #4a5568;
        }
        .highlight {
            background-color: #edf2f7;
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 14px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #718096;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <img src="https://i.imgur.com/23Q8lZE.png" alt="Stak Logo" class="logo">
            <h1>Contract Successfully Activated</h1>
        </div>
        <div class="content">
            <p>Dear {{ $customer_name ?? 'Valued Client' }},</p>

            <p>We’re pleased to inform you that the contract <strong>"{{ $contractName }}"</strong> has been <strong>officially activated</strong>.</p>

            <div class="highlight">
                All required signatures have been received, and the contract is now in full effect.
            </div>

            <p>A final copy of the signed contract is attached to this email for your records.</p>

            <p>If you have any questions or require further assistance, please don’t hesitate to reach out to us.</p>

            <p>Thank you for your prompt cooperation!</p>

            <p>Best regards, <br><strong>The Stak Team</strong></p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Stak. All rights reserved.
        </div>
    </div>
</body>
</html>
