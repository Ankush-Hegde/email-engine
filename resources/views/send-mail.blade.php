<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Mail</title>
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

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #0078d4;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
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
        <h1>Send Mail</h1>
        <form id="sendMailForm">
            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" placeholder="Enter the subject" required>

            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="5" placeholder="Enter the email content" required></textarea>

            <label for="recipient">Recipient Email:</label>
            <input type="email" id="recipient" name="recipient" placeholder="Enter recipient's email address" required>

            <button type="submit" class="btn">Send Mail</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('sendMailForm');

        form.addEventListener('submit', event => {
            event.preventDefault();

            const subject = document.getElementById('subject').value;
            const content = document.getElementById('content').value;
            const recipient = document.getElementById('recipient').value;

            const requestBody = {
                message: {
                    subject: subject,
                    body: {
                        contentType: "Text",
                        content: content,
                    },
                    toRecipients: [
                        {
                            emailAddress: {
                                address: recipient,
                            }
                        }
                    ]
                }
            };

            fetch('http://localhost:8000/api/v1/email/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestBody),
            })
                .then(response => {
                    if (response.ok) {
                        alert('Mail sent successfully!');
                        form.reset();
                    } else {
                        return response.json().then(error => {
                            alert(`Failed to send mail: ${error.message}`);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error sending mail:', error);
                    alert('An error occurred while sending the mail.');
                });
        });
    </script>

</body>
</html>
