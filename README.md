# to start the project
 1. start the xamp/sql server
 2. create the database email_engine
 3. migrate the database using 
 <code>php artisan migrate</code> command
 
 4. change .env.example to .env
 5. run the following to generate the encryption key <code>php artisan key:generate</code>.
 6. set up elastic search by using this command<code>php artisan elasticsearch:setup</code>
 7. start the server using <code>php artisan start</code> command

# API
### 1. generate_url
<code>get</code><code>http://localhost:8000/api/v1/oauth/outlook/generate_url</code>

response
```
{
    "redirect_uri": "https://login.microsoftonline.com/common/oauth2/v2.0/authorize?client_id=2fc8eb46-76ba-4ca4-b70f-aed8d77e3a56&response_type=code&redirect_uri=http%3A%2F%2Flocalhost%3A8000%2Fapi%2Fv1%2Foauth%2Foutlook%2Fcallback&scope=openid+offline_access+profile+email+Mail.ReadWrite+Mail.Read+Mail.Send"
}
```
### 1. callback
<code>get</code><code>http://localhost:8000/api/v1/oauth/outlook/callback?code=E.V533_BW4.4.D.73cf-32-67-7a-ef0</code>

response
```
this will return view
```

 ## TASKS: 
 [startedOn:07/12/2024] - [endedOn:09/12/2024]
 1. db elastic search -> SUCCESS!! [startedOn:07/12/2024] - [endedOn:07/12/2024]
 2. api using oauth -> SUCCESS!! [startedOn:08/12/2024] - [endedOn:08/12/2024]
 3. Email Data Synchronization -> (//todo//)
 4. scalability -> SUCCESS!! [startedOn:08/12/2024] - [endedOn:08/12/2024]
 5. best practic -> SUCCESS!! [startedOn:08/12/2024] - [endedOn:08/12/2024]
 6. Extensibility -> (//todo//)
 7. Deliverables docker -> SUCCESS!! [startedOn:07/12/2024] - [endedOn:07/12/2024]