<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />

        <title>OTP Code</title>

        <style>
            /* Reset styles */
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }
            body {
                font-family: 'poppins';
            }

            /* Center container */
            .container {
                display: flex;
                justify-content: center;
                flex-direction: column;
                align-items: center;
                height: 100vh;
                padding: 20px;
            }

            /* Card styling */
            .card {
                background: #fff;
                border: 1px solid #e0e0e0;
                border-radius: 20px;
                box-shadow: 0 4px 8px rgba(154, 255, 238, 0.1);
                max-width: 600px;
                padding: 4rem 2rem;
            }
            .logo {
                width: 90px;
                margin: auto;
            }
            /* Logo styling */
            .logo-img {
                width: 100%;
            }

            /* Header styling */
            .title {
                font-size: 30px;
                text-align: center;
                color: #303030;
                margin: 15px 0;
            }
            /* line or divider */
            .divider {
                margin: 40px 0;
                width: 100%;
                background-color: #023c4233;
                height: 1px;
            }

            /* Paragraph styling */
            .card-text {
                font-size: 16px;
                text-align: justify;
                line-height: 1.8;
                color: #555;
                font-weight: 400;
                margin-bottom: 45px;
            }

            /* OTP box styling */
            .otp-box {
                margin-bottom: 45px;
                padding: 37px 0;
                background-color: #f0f9f9;
                border: 1px solid #d0e7e7;
                border-radius: 10px;
                text-align: center;
                color: #555;
                font-size: 1.5rem;
            }

            /* Expiry note */
            .expiry {
                font-size: 16px;
                text-align: justify;
                line-height: 1.8;
                color: #555;
                font-weight: 600;
                margin-bottom: 15px;
            }
            .note {
                font-size: 16px;
                line-height: 1.8;
                color: #555;
                font-weight: 400;
            }
            /* Footer note */
            .reason {
                font-size: 13px;
                text-align: center;
                font-weight: 400;
                color: #9c9c9c;
                margin-top: 20px;
            }

            /* Responsive adjustments */
            @media (max-width: 480px) {
                .title {
                    font-size: 25px;
                }

                .card-text,
                .note {
                    font-size: 14px;
                    text-align: center;
                }
                .expiry {
                    text-align: center;
                }
                .reason {
                    font-size: 12px;
                }
            }
        </style>
    </head>

    <body>
        <main class="container">
            <div class="card">
                <div class="logo">
                    <img class="logo-img" src="https://i.imgur.com/23Q8lZE.png" alt="stak-logo" />
                </div>
                <h2 class="title">Your OTP code</h2>
                <div class="divider"></div>
                <p class="card-text">
                    You have received a request to log in to your account. Below is your one-time password (
                    <strong>OTP</strong>
                    ) to complete the login process.
                </p>
                <div class="otp-box">
                    <strong>{{ $otp }}</strong>
                </div>
                <p class="expiry">This code will expire within 5 minutes</p>
                <p class="note">If you don’t recognize this, you can safely ignore this email.</p>
            </div>
            <footer>
                <p class="reason">
                    You received this email to provide you an OTP to assist you to log in to your Stak Account.
                </p>
            </footer>
        </main>
    </body>
</html>
