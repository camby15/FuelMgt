<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>⏰ Task Reminder: {{ $taskName }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            background: #fff;
            padding: 20px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }
        .header {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .subtext {
            font-size: 14px;
            color: #888;
            margin-bottom: 20px;
        }
        .info {
            margin: 15px 0;
            font-size: 16px;
            color: #444;
        }
        .btn {
            display: inline-block;
            margin: 10px 5px 0 0;
            padding: 10px 15px;
            background-color: #069a9a;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            font-size: 14px;
        }
        .footer {
            margin-top: 25px;
            font-size: 12px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">⏰ Reminder: {{ $taskName }}</div>
        <div class="subtext">This task is due in {{ $timeRemaining }} minutes.</div>

        <div class="info">
            <strong>Due:</strong> {{ $dueDateTime }} ({{ $timezone }})<br />
            <strong>Priority:</strong> {{ $priority }}<br />
            <strong>Project:</strong> {{ $projectName }}<br />
        </div>

        <a href="{{ $completeLink }}" class="btn">✅ Mark Complete</a>
        <a href="{{ $taskLink }}" class="btn" style="background-color:#555;">📝 View Task</a>

        <div class="footer">
            You’re receiving this because you have 10-minute task alerts enabled.<br />
            <a href="{{ $requestExtensionLink }}">Request more time</a>
        </div>
    </div>
</body>
</html>
