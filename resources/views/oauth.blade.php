<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oauth</title>
    <style>
        body {
            background-color: black;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            text-align: center;
        }

        button {
            font-size: 18px;
            padding: 10px 20px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .outlook-btn {
            background-color: #0078D4; 
            color: white;
        }

        .google-btn {
            background-color: #DB4437;
            color: white;
            border: 2px solid #DB4437;
        }

        button:hover {
            opacity: 0.9;
        }
    </style>
    <script>
        async function handleOutlookOAuth() {
            try {
                const response = await fetch('http://localhost:8000/api/v1/oauth/outlook/generate_url');
                if (!response.ok) {
                    throw new Error('Failed to fetch the redirect URL');
                }

                const data = await response.json();

                const redirectUrl = data.redirect_uri;

                window.location.href = redirectUrl;
            } catch (error) {
                console.error('Error during OAuth process:', error);
                window.location.href = 'http://localhost:8000/error';
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <button class="outlook-btn" onclick="handleOutlookOAuth()">Outlook Oauth</button><br>
        
        <button class="google-btn">Google Oauth</button>
    </div>
</body>
</html>
