<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '419 Page Expired')</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #fff3e0;
            margin: 0;
        }

        .container {
            text-align: center;
        }

        .error-code {
            font-size: 8em;
            color: #ff9800;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .expired-text {
            font-size: 1.2em;
            color: #555;
            margin-bottom: 30px;
        }

        .back-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ffc107;
            color: #212121;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-link:hover {
            background-color: #f9a825;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">
            419
        </div>
        <p class="expired-text">Page Expired. Please refresh and try again.</p>
        <a href="javascript:history.back()" class="back-link">Go back</a>
    </div>
</body>
</html>