<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopSieuReOk</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        table {
            border-spacing: 0;
            border-collapse: collapse;
        }

        img {
            display: block;
            border: 0;
        }

        a {
            text-decoration: none;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header img {
            max-width: 100px;
        }

        .content {
            padding: 20px;
            font-family: Arial, sans-serif;
            color: #333333;
            line-height: 1.6;
        }

        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            border-radius: 5px;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f4f4f4;
            color: #888888;
            font-size: 12px;
        }

        @media only screen and (max-width: 600px) {
            .container {
                width: 100%;
                padding: 0;
            }

            .content {
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <table width="100%" bgcolor="#f4f4f4" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td>
                <table class="container">
                    <!-- Header -->
                    <tr>
                        <td class="header">
                            {{-- <img src="https://your-logo-url.com/logo.png" alt="Company Logo"> --}}
                            <h1>Company ShopSieuReOk</h1>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td class="content">
                            <h2>Hello, {{ $name }}</h2>
                            <p>Thank you for choosing our service. We are excited to have you on board.</p>
                            <p>Here’s a quick summary of your recent purchase:</p>
                            <ul>
                                <li>Order Status: {{ $status }}</li>
                                <li>Total Amount: {{ number_format($total) }} đ</li>
                            </ul>
                            <p>If you have any questions, feel free to reply to this email or contact us at our support
                                center.</p>
                            <a href="{{ route('listorders') }}" class="button">View Your Order</a>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td class="footer">
                            <p>Follow us on social media</p>
                            <p>
                                <a href="https://www.facebook.com/profile.php?id=100041666683033">Facebook</a> |
                                <a href="https://www.instagram.com/m.haijr_/">Instagram</a>
                            </p>
                            <p>© 2024 Your Company, Inc. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
