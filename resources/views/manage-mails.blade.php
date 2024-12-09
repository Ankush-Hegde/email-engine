<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Mails</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .email {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 20px;
            background-color: #0078d4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

    <div class="container">
        <p class="email" id="user-email"></p>
        <button class="btn" id="getMailsBtn">Get My Mails</button>
    </div>

    <script>
        const userEmail = localStorage.getItem('user_email');
        document.getElementById('user-email').innerText = `User Email: ${userEmail}`;

        document.getElementById('getMailsBtn').addEventListener('click', () => {
            fetch(`http://localhost:8000/api/v1/mails?email=${userEmail}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Mails:', data);
                })
                .catch(error => console.error('Error fetching mails:', error));
        });
    </script>

</body>
</html>
