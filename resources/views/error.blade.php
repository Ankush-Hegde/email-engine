<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body {
            background-color: black;
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }

        h1 {
            font-size: 40px;
            margin-bottom: 20px;
        }

        p {
            font-size: 20px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #0078D4;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div>
        <h1>Error Occurred</h1>
        <p>Something went wrong during the OAuth process. Please try again later.</p>
        <button class="btn" onclick="window.location.href = '/'">Go Back to login</button>
    </div>
</body>
</html>
