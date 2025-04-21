<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }

        .container {
            text-align: center;
        }

        .error-code {
            font-size: 8em;
            color: #333;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .error-code span {
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: #ff6b6b;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            opacity: 0;
            animation: glitch 2s infinite alternate;
        }

        .error-code span:nth-child(2) {
            background-color: #4ecdc4;
            animation-delay: 0.5s;
        }

        @keyframes glitch {
            0% {
                transform: translate(0);
                opacity: 0;
            }
            10% {
                transform: translate(-5px, 5px);
                opacity: 1;
            }
            20% {
                transform: translate(5px, -5px);
                opacity: 0;
            }
            30% {
                transform: translate(-2px, 2px);
                opacity: 1;
            }
            40% {
                transform: translate(2px, -2px);
                opacity: 0;
            }
            50% {
                transform: translate(0);
                opacity: 1;
            }
            60% {
                transform: translate(-3px, 3px);
                opacity: 0;
            }
            70% {
                transform: translate(3px, -3px);
                opacity: 1;
            }
            80% {
                transform: translate(-1px, 1px);
                opacity: 0;
            }
            90% {
                transform: translate(1px, -1px);
                opacity: 1;
            }
            100% {
                transform: translate(0);
                opacity: 0;
            }
        }

        .not-found-text {
            font-size: 1.5em;
            color: #777;
            margin-bottom: 30px;
        }

        .back-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">
            404
            <span>404</span>
            <span>404</span>
        </div>
        <p class="not-found-text">Oops! The page you're looking for could not be found.</p>
        <a href="{{url('/')}}" class="back-link">Go back home</a>
    </div>
</body>
</html>