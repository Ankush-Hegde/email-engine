# to start the project
 1. start the xamp/sql server
 2. create the database email_engine
 3. migrate the database using 
 <code>php artisan migrate</code> command
 
 4. change .env.example to .env
 5. run the following to generate the encryption key <code>php artisan key:generate</code>.
 6. start and then set up elastic search by using this command<code>php artisan elasticsearch:setup</code>
 7. start the server using <code>php artisan start</code> command
 8. navigate to <code>localhost:8000</code> in browser and click on the outlook oauth 
 
# API
### 1. generate_url
<code>get</code><code>http://localhost:8000/api/v1/oauth/outlook/generate_url</code>

response
```
{
    "redirect_uri": "https://login.microsoftonline.com/common/oauth2/v2.0/authorize?client_id=2fc8eb46-76ba-4ca4-b70f-aed8d77e3a56&response_type=code&redirect_uri=http%3A%2F%2Flocalhost%3A8000%2Fapi%2Fv1%2Foauth%2Foutlook%2Fcallback&scope=openid+offline_access+profile+email+Mail.ReadWrite+Mail.Read+Mail.Send"
}
```
### 2. callback
<code>get</code><code>http://localhost:8000/api/v1/oauth/outlook/callback?code=E.V533_BW4.4.D.73cf-32-67-7a-ef0</code>

response
```
this will return view
```
| Registration | Manage Mails |
|--------------|--------------|
| ![registeration-git](https://github.com/user-attachments/assets/bff9a868-2c01-4d1c-8bdd-1fbce5258b0c) | ![manage-mails-git](https://github.com/user-attachments/assets/4e998b86-0d2d-4644-b015-7dc2fb816978) |

### 3. fetchMail from the microsoft graph api
<code>post</code><code>http://localhost:8000/api/v1/email/fetch</code>
<br>
body json
```
{
    "email" : "kellbooby8@gmail.com" // used as a session for the simplicity
}
```

response
```
[
    {
        "_index": "emails",
        "_id": "AQMkADAwATM3ZmYAZS02ZTLTAwCgBGAAADMAO-kHNvAU_QWj_itP_POwcA4KYOSSojDkC6diOE8kTG-gAAAgEJAAAA4KYOSSojDkC6diOE8kTG-gABGOFl4gAAAA==",
        "_score": 0.18232156,
        "_source": {
            "user_id": 2,
            "subject": "Meeting Reminder",
            "body": "This is a reminder for our meeting.",
            "from": "kellboby8@gmail.com",
            "to": [
                {
                    "emailAddress": "ar@gmail.com"
                }
            ],
            "cc": [],
            "bcc": [],
            "is_read": true,
            "received_date": "2024-12-08T12:47:45Z",
            "sent_date": "2024-12-08T12:47:44Z"
        }
    },
    {
        "_index": "emails",
        "_id": "AQMkADAwATM3ZmYACLTAwCgBGAAADMAO-kHNvAU_QWj_itP_POwcA4KYOSSojDkC6diOE8kTG-gAAAgEJAAAA4KYOSSojDkC6diOE8kTG-gABGOFl4QAAAA==",
        "_score": 0.18232156,
        "_source": {
            "user_id": 2,
            "subject": "Meeting Reminder",
            "body": "This is a reminder for our meeting.",
            "from": "kellboby8@gmail.com",
            "to": [
                {
                    "emailAddress": "ar@gmail.com"
                }
            ],
            "cc": [],
            "bcc": [],
            "is_read": true,
            "received_date": "2024-12-08T12:47:19Z",
            "sent_date": "2024-12-08T12:47:19Z"
        }
    }
]
```
![fetch-email-git](https://github.com/user-attachments/assets/16b30b37-07a7-4aa1-bb0f-3e6b6d3ddb32)

### 4. send Mail
<code>post</code><code>http://localhost:8000/api/v1/email/send</code>
<br>body<br>
```
{
    "email": "kellbooby8@gmail.com",
    "subject": "test email",
    "body": "success!!",
    "toRecipients": ["ar@gmail.com"]
}
```
response<br>
```
{
    "message": "Email sent successfully"
}
```
| Send-mail | Send-Success |
|--------------|--------------|
| ![send-email](https://github.com/user-attachments/assets/bf9e5f3c-3ff1-40fd-a8fd-af58a8b1ec54) | ![sent-success-git](https://github.com/user-attachments/assets/d43ebd25-3024-4a45-9c0b-adbe18640bce) |

# DB design
 as of the requirements,
 1. need to have user_table, Oauth_table
 where Oauth_table structure is

  ```markdown
  | :---: | :---: |
  | id | int |
  | user_id | int |
  | provider_type | (google, microsoft) |
  | access_token | string |
  | refresh_token | string |
  | expair_in | dateTime |
  ```
  we need to link oAuth table to user table with local user_id<br>
  ```NOTE: as of now, everything is stored in the user table which need to  be corrected```

  # middleware
  we need to have session middleware, where session will be having the type to differenciate the outlook auth and the google auth, now lets assume that everything is hapaning only in the outlook email 

 ## TASKS: 
 [startedOn:07/12/2024] - [endedOn:09/12/2024]
 1. db elastic search -> SUCCESS!! [startedOn:07/12/2024] - [endedOn:07/12/2024]
 2. api using oauth -> SUCCESS!! [startedOn:08/12/2024] - [endedOn:08/12/2024]
 3. Email Data Synchronization -> SUCCESS!! [startedOn:09/12/2024] - [endedOn:09/12/2024] we can improve it by automatically syncing the email
 4. scalability -> SUCCESS!! [startedOn:08/12/2024] - [endedOn:08/12/2024]
 5. best practic -> SUCCESS!! [startedOn:08/12/2024] - [endedOn:08/12/2024]
 6. Extensibility -> SUCCESS!! [startedOn:09/12/2024] - [endedOn:09/12/2024]
 7. Deliverables docker -> SUCCESS!! [startedOn:07/12/2024] - [endedOn:07/12/2024]

view updated project on :- <code> https://github.com/Ankush-Hegde/email-engine </code>
