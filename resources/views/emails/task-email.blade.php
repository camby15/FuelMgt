<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <title>New Activity Assigned: {{ $activityName }}</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            padding: 30px;
            text-align: center;
        }

        .logo {
            width: 120px;
            margin: auto;
        }

        .logo-img {
            width: 100%;
        }

        .title {
            font-size: 26px;
            color: #333;
            margin-top: 20px;
        }

        .card-text {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
            margin: 20px 0;
        }
        
        .activity-details {
            text-align: left;
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .detail-row {
            margin-bottom: 10px;
            display: flex;
        }
        
        .detail-label {
            font-weight: bold;
            min-width: 120px;
            color: #333;
        }
        
        .detail-value {
            flex: 1;
            color: #555;
        }

        .btn {
            display: inline-block;
            background-color: #069a9a;
            color: #fff;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
            margin-top: 15px;
        }

        .btn:hover {
            background-color: #e65c00;
        }

        .footer {
            font-size: 12px;
            color: #888;
            margin-top: 20px;
        }
        
        strong {
            color: #069a9a;
        }

        @media (max-width: 480px) {
            .title {
                font-size: 22px;
            }
            .card-text {
                font-size: 14px;
            }
            .detail-row {
                flex-direction: column;
            }
            .detail-label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <main class="container">
        <div class="card">
            <div class="logo">
                <img class="logo-img" src="https://i.imgur.com/23Q8lZE.png" alt="Company Logo" />
            </div>
            <h2 class="title">New Activity: {{ $activityName }}</h2>
            <p class="card-text">
                A new activity has been assigned to you in the CRM system. Please review the details below:
            </p>
            
            <div class="activity-details">
                <div class="detail-row">
                    <span class="detail-label">Activity Name:</span>
                    <span class="detail-value">{{ $activityName }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Assigned To:</span>
                    <span class="detail-value">{{ $assignedTo }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Assigned By:</span>
                    <span class="detail-value">{{ $assignedBy ?? 'System'  }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Start Time:</span>
                    <span class="detail-value">{{ $startTime }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">End Time:</span>
                    <span class="detail-value">{{ $endTime ?? 'Same as start time'  }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Priority:</span>
                    <span class="detail-value">{{ $priority }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">{{ $status }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Description:</span>
                    <span class="detail-value">{{ $description }}</span>
                </div>
            </div>
            
            {{-- <a href="{{ $actionUrl }}" class="btn">View Activity in CRM</a> --}}
            
            <p class="footer">
                You received this email because you are assigned to this activity in the CRM system of <strong>{{ $companyName }}</strong>.
                <br><br>
                Need help? Contact support at <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>
            </p>
        </div>
    </main>
</body>
</html>