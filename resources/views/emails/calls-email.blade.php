<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <title>Call Activity: {{ $callSubject }}</title>

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
        
        .call-details {
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
        
        .call-phone {
            font-size: 18px;
            font-weight: bold;
            color: #069a9a;
            margin: 10px 0;
        }

        .btn {
            display: inline-block;
            background-color: #ff6600;
            color: #fff;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s;
            margin: 10px 5px;
        }
        
        .btn-secondary {
            background-color: #069a9a;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .footer {
            font-size: 12px;
            color: #888;
            margin-top: 20px;
        }
        
        strong {
            color: #069a9a;
        }
        
        .call-reminder {
            color: #ff6600;
            font-weight: bold;
            margin: 15px 0;
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
            .btn {
                display: block;
                width: 100%;
                margin: 10px 0;
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
            <h2 class="title">Call Activity: {{ $callSubject }}</h2>
            <p class="card-text">
                {{ $isAssigned ? 'A call has been assigned to you' : 'You have a scheduled call' }} in the CRM system.
            </p>
            
            <div class="call-details">
                <div class="detail-row">
                    <span class="detail-label">Regarding:</span>
                    <span class="detail-value">{{ $regarding }}</span>
                </div>
                {{-- <div class="detail-row">
                    <span class="detail-label">With:</span>
                    <span class="detail-value">{{ $contactName }} ({{ $companyName }})</span>
                </div> --}}
                {{-- <div class="call-phone">
                    {{ $phoneNumber }}
                </div> --}}
                <div class="detail-row">
                    <span class="detail-label">Scheduled:</span>
                    <span class="detail-value">{{ $callDateTime }}</span>
                </div>
                {{-- <div class="detail-row">
                    <span class="detail-label">Duration:</span>
                    <span class="detail-value">{{ $duration }} minutes</span>
                </div> --}}
                <div class="detail-row">
                    <span class="detail-label">Priority:</span>
                    <span class="detail-value">{{ $priority }}</span>
                </div>
                {{-- <div class="detail-row">
                    <span class="detail-label">Call Type:</span>
                    <span class="detail-value">{{ $callType }}</span>
                </div> --}}
                <div class="detail-row">
                    <span class="detail-label">Description:</span>
                    <span class="detail-value">{{ $description }}</span>
                </div>
            </div>
            
            <div class="call-reminder">
                ⏰ Reminder: This call is scheduled for {{ $callDateTime }}
            </div>
            
            {{-- <div>
                <a href="tel:{{ $phoneNumber }}" class="btn">Call Now</a>
                <a href="{{ $crmLink }}" class="btn btn-secondary">View in CRM</a>
            </div> --}}
            
            <p class="footer">
                This call activity was {{ $isAssigned ? 'assigned by ' + $assignedBy : 'created' }} in the CRM system of <strong>{{ $companyName }}</strong>.
                <br><br>
                Need to reschedule? Contact <a href="mailto:{{ $coordinatorEmail }}">{{ $coordinatorName }}</a>
            </p>
        </div>
    </main>
</body>
</html>