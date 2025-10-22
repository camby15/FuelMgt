<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>🎉 Event Reminder: {{ $eventTitle }}</title>
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
        <div class="header">🎉 Upcoming Event: {{ $eventTitle }}</div>
        <div class="subtext">This event starts in {{ $timeUntilEvent }} minutes.</div>

        <div class="info">
            <strong>Date & Time:</strong> {{ $eventDateTime }} ({{ $timezone }})<br />
            <strong>Location:</strong> {{ $eventLocation }}<br />
            <strong>Host:</strong> {{ $eventHost }}<br />
            <strong>Description:</strong> {{ $eventDescription }}<br />
        </div>

        <a href="{{ $joinLink }}" class="btn">Join / Attend</a>
        <a href="{{ $calendarLink }}" class="btn" style="background-color:#555;">Add to Calendar</a>

        <div class="footer">
            You're receiving this reminder because you're registered for the event.<br />
            <a href="{{ $manageRSVPLink }}">Manage RSVP</a>
        </div>
    </div>
</body>
</html>
