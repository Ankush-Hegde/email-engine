<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
            text-align: center;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            color: #0078d4;
        }

        p {
            font-size: 18px;
            color: #333;
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
        <h1>Registration Successful!</h1>
        <p>Welcome, <span id="user-name">{{ $user->name }}</span></p>
        <p>Email: <span id="user-email">{{ $user->email }}</span></p>
        <button class="btn" id="nextPageBtn">Go to Manage Mails</button>
    </div>

    <script>
        // Storing the user details in localStorage
        const userName = "{{ $user->name }}";
        const userEmail = "{{ $user->email }}";

        localStorage.setItem('user_name', userName);
        localStorage.setItem('user_email', userEmail);

        // Redirect to the manage mails page
        document.getElementById('nextPageBtn').addEventListener('click', () => {
            window.location.href = '/manage-mails';
        });
    </script>

</body>
</html>
