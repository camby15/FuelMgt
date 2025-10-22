<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <title>Exclusive Offers Just for You!</title>

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
        strong{
            color: #069a9a;
        }

        @media (max-width: 480px) {
            .title {
                font-size: 22px;
            }
            .card-text {
                font-size: 14px;
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
            <h2 class="title">{{ $subject }}</h2>
            <p class="card-text">
                {{ $emailMessage }}
            </p>
            <!-- 
            <a href="#" class="btn">Shop Now</a> 
            -->
            <p class="footer">
                You received this email because you are a valued customer and we appreciate your business. This Email is sent by <strong>{{ $companyName }}</strong>.
            </p>
        </div>
    </main>
</body>
</html>
