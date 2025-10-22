<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Email Notification')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .email-wrapper {
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
    <div class="email-wrapper">
        @yield('content')
    </div>
</body>
</html>