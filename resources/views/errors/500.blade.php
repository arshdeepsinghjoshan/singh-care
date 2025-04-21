<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '500 Internal Server Error')</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #eee;
            margin: 0;
        }

        .container {
            text-align: center;
        }

        .error-code {
            font-size: 8em;
            color: #9e9e9e;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .server-error-text {
            font-size: 1.2em;
            color: #555;
            margin-bottom: 30px;
        }

        .back-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #78909c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-link:hover {
            background-color: #546e7a;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">
            500
        </div>
        <p class="server-error-text">Whoops! Something went wrong on our server.</p>
        <a href="{{ url('/') }}" class="back-link">Go back home</a>
    </div>
</body>
</html>