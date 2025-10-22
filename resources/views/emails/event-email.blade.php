<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <title>Meeting Scheduled: {{ $meetingTitle }}</title>

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
        
        .meeting-details {
            text-align: left;
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .detail-row {
            margin-bottom: 12px;
            display: flex;
        }
        
        .detail-label {
            font-weight: bold;
            min-width: 130px;
            color: #333;
        }
        
        .detail-value {
            flex: 1;
            color: #555;
        }
        
        .meeting-time {
            background-color: #e6f7f7;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border-left: 4px solid #069a9a;
        }
        
        .participants {
            margin-top: 15px;
        }
        
        .participant-list {
            list-style-type: none;
            margin-top: 8px;
        }
        
        .participant-item {
            display: flex;
            align-items: center;
            margin-bottom: 6px;
        }
        
        .participant-badge {
            width: 24px;
            height: 24px;
            background-color: #069a9a;
            color: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-size: 12px;
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
            margin: 8px 5px;
        }
        
        .btn-secondary {
            background-color: #069a9a;
        }
        
        .btn-outline {
            background: transparent;
            border: 1px solid #069a9a;
            color: #069a9a;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .footer {
            font-size: 12px;
            color: #888;
            margin-top: 20px;
            line-height: 1.5;
        }
        
        strong {
            color: #069a9a;
        }
        
        .calendar-links {
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
                min-width: auto;
            }
            .btn {
                display: block;
                width: 100%;
                margin: 8px 0;
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
            <h2 class="title">{{ $isUpdate ? 'Meeting Updated' : 'Meeting Scheduled' }}: {{ $meetingTitle }}</h2>
            <p class="card-text">
                {{ $isUpdate ? 'Your meeting details have been updated' : 'You have been invited to a meeting' }}. 
                {{ $isOrganizer ? 'You are the organizer.' : 'Please find the details below.' }}
            </p>
            
            <div class="meeting-details">
                <div class="meeting-time">
                    <div class="detail-row">
                        <span class="detail-label">Date:</span>
                        <span class="detail-value">{{ $meetingDate }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Time:</span>
                        <span class="detail-value">{{ $startTime }} - {{ $endTime }} ({{ $timezone }})</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Duration:</span>
                        <span class="detail-value">{{ $duration }}</span>
                    </div>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Location:</span>
                    <span class="detail-value">
                        {{ $isVirtual ? 'Virtual Meeting' : $physicalLocation }}
                        {{ $isVirtual ? '('+$meetingPlatform+')' : '' }}
                    </span>
                </div>
                
                {{#if $isVirtual}}
                <div class="detail-row">
                    <span class="detail-label">Join Link:</span>
                    <span class="detail-value">
                        <a href="{{ $meetingLink }}" target="_blank">{{ $meetingLink }}</a>
                    </span>
                </div>
                {{/if}}
                
                <div class="detail-row">
                    <span class="detail-label">Organizer:</span>
                    <span class="detail-value">{{ $organizerName }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Agenda:</span>
                    <span class="detail-value">{{ $meetingAgenda }}</span>
                </div>
                
                <div class="participants">
                    <div class="detail-row">
                        <span class="detail-label">Participants:</span>
                        <span class="detail-value">({{ $participantCount }})</span>
                    </div>
                    <ul class="participant-list">
                        {{#each $participants}}
                        <li class="participant-item">
                            <span class="participant-badge">{{ substring($this.name, 0, 1) }}</span>
                            {{ $this.name }} {{ $this.email ? '('+$this.email+')' : '' }}
                        </li>
                        {{/each}}
                    </ul>
                </div>
            </div>
            
            <div class="calendar-links">
                <a href="{{ $calendarLink }}" class="btn">Add to Calendar</a>
                <a href="{{ $crmLink }}" class="btn btn-secondary">View in CRM</a>
                {{#if $isVirtual}}
                <a href="{{ $meetingLink }}" class="btn btn-outline">Join Meeting</a>
                {{/if}}
            </div>
            
            <p class="footer">
                This meeting was {{ $isUpdate ? 'updated' : 'created' }} by {{ $organizerName }} in the {{ $companyName }} CRM system.
                <br>
                {{#if $isVirtual}}
                Meeting ID: {{ $meetingId }} | Passcode: {{ $meetingPasscode }}
                <br>
                {{/if}}
                Need to make changes? {{ $isOrganizer ? 'Update in CRM' : 'Contact the organizer' }}.
            </p>
        </div>
    </main>
</body>
</html>