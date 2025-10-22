
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <title>New Ticket Assignment</title>

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
            height: 100vh;
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
        
        .ticket-info {
            background-color: #f5f5f5;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            text-align: left;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 12px;
        }
        
        .info-label {
            font-weight: bold;
            color: #333;
            min-width: 100px;
        }
        
        .info-value {
            color: #555;
            flex-grow: 1;
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
            .info-row {
                flex-direction: column;
            }
            .info-label {
                margin-bottom: 4px;
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
            <h2>Hello {{ $ticket->agent->name ?? 'Agent' }},</h2>
            <h2 class="title">You have been assigned a new ticket</h2>
            <p class="card-text">
                Please find below the details of your newly assigned ticket:
            </p>
            
            <div class="ticket-info">
                <div class="info-row">
                    <div class="info-label">Subject:</div>
                    <div class="info-value">{{ $ticket->subject }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Message:</div>
                    <div class="info-value">{{ $ticket->description }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Ticket ID:</div>
                    <div class="info-value"> {{ $ticket->ticket_id }}</div>
                </div>
            </div>

            
            <p class="footer">
                This email was automatically sent by the <strong>{{ $ticket->customer }}</strong> ticket system.
            </p>
        </div>
    </main>
</body>
</html>