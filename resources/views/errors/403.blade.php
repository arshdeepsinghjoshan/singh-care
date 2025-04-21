<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '403 Forbidden')</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f9f9f9;
            margin: 0;
        }

        .container {
            text-align: center;
        }

        .error-code {
            font-size: 8em;
            color: #d32f2f;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .forbidden-text {
            font-size: 1.5em;
            color: #777;
            margin-bottom: 30px;
        }

        .back-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #673ab7;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-link:hover {
            background-color: #512da8;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">
            403
        </div>
        <p class="forbidden-text">Sorry, you don't have permission to access this page.</p>
        <a href="{{ url()->previous() }}" class="back-link">Go back</a>
    </div>
</body>
</html>