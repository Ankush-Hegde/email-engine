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
            max-width: 600px;
            margin: 0 auto;
        }

        .email {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
            margin: 10px 0;
            background-color: #0078d4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .mail-list {
            margin-top: 20px;
        }

        .mail-item {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        .mail-item:last-child {
            border-bottom: none;
        }

        .mail-subject {
            font-weight: bold;
            font-size: 16px;
        }

        .mail-sender {
            color: #555;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="container">
        <p class="email" id="user-email"></p>
        <button class="btn" id="getMailsBtn">Get My Mails</button>
        <button class="btn" id="sendMailBtn">Send Mail</button>
        <div class="mail-list" id="mail-list"></div>
    </div>

    <script>
        const userEmail = localStorage.getItem('user_email');
        document.getElementById('user-email').innerText = `User Email: ${userEmail}`;

        document.getElementById('getMailsBtn').addEventListener('click', () => {
            fetch('http://localhost:8000/api/v1/email/fetch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email: userEmail }),
            })
                .then(response => response.json())
                .then(data => {
                    const mailList = document.getElementById('mail-list');
                    mailList.innerHTML = '';

                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(mail => {
                            const mailItem = document.createElement('div');
                            mailItem.classList.add('mail-item');
                            mailItem.innerHTML = `
                                <p class="mail-subject">Subject: ${mail._source.subject}</p>
                                <p class="mail-sender">From: ${mail._source.from}</p>
                            `;
                            mailList.appendChild(mailItem);
                        });
                    } else {
                        mailList.innerHTML = '<p>No emails found.</p>';
                    }
                })
                .catch(error => console.error('Error fetching mails:', error));
        });

        document.getElementById('sendMailBtn').addEventListener('click', () => {
            window.location.href = '/send-mail';
        });
    </script>

</body>
</html>