<?php

namespace App\Services\Outlook;

use Illuminate\Support\Facades\Http;

class OutlookService
{
    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;

    public function __construct()
    {
        $this->clientId = config('services.azure.client_id');
        $this->clientSecret = config('services.azure.client_secret');
        $this->redirectUri = config('services.azure.redirect');
    }

    public function generateAuthUrl(): string
    {
        $baseUrl = "https://login.microsoftonline.com/common/oauth2/v2.0/authorize"; // keep in env

        $queryParams = http_build_query([
            Constants::CLIENT_ID => $this->clientId,
            Constants::RESPONSE_TYPE => Constants::CODE,
            Constants::REDIRECT_URI => $this->redirectUri,
            Constants::SCOPE => Constants::OPENID . ' ' . Constants::OFFLINE_ACCESS . ' ' . Constants::PROFILE . ' ' . Constants::EMAIL . ' ' . Constants::MAIL_READEWRITE . ' ' . Constants::MAIL_READ . ' ' . Constants::MAIL_SEND,
        ]);

        return "{$baseUrl}?{$queryParams}";
    }

    public function getTokens(string $code): array
    {
        $url = "https://login.microsoftonline.com/common/oauth2/v2.0/token"; // keep in env

        $response = Http::asForm()->withOptions([Constants::VERIFY => false])->post($url, [ // ssl certificate ignored
            Constants::CLIENT_ID => $this->clientId,
            Constants::CLIENT_SECRET => $this->clientSecret,
            Constants::CODE => $code,
            Constants::REDIRECT_URI => $this->redirectUri,
            Constants::GRANT_TYPE => Constants::AUTHORIZATION_CODE,
        ]);

        return $response->json();
    }

    public function refreshAccessToken($refreshToken)
    {
        $url = "https://login.microsoftonline.com/common/oauth2/v2.0/token";

        $response = Http::asForm()->withOptions([Constants::VERIFY => false])->post($url, [ // ssl certificate ignored 
            Constants::CLIENT_ID => $this->clientId,
            Constants::CLIENT_SECRET => $this->clientSecret,
            Constants::REFRESH_TOKEN => $refreshToken,
            Constants::GRANT_TYPE => Constants::REFRESH_TOKEN,
            Constants::REDIRECT_URI => $this->redirectUri,
        ]);

        return $response->json();
    }

    public function fetchEmails($accessToken, $top = 50, $skip = 0)
    {
        $url = "https://graph.microsoft.com/v1.0/me/messages";

        $response = Http::withHeaders([
            Constants::AUTHORIZATION => Constants::BEARER . ' ' . $accessToken,
            Constants::ACCEPT => 'application/json',
        ])->withOptions([Constants::VERIFY => false])->get($url, [
            '$top' => $top,
            '$skip' => $skip,
        ]);

        return $response->json();
    }

    public function sendEmail($accessToken, $subject, $bodyContent, $recipients)
    {
        $url = "https://graph.microsoft.com/v1.0/me/sendMail";

        $response = Http::withHeaders([
            Constants::AUTHORIZATION => Constants::BEARER . ' ' . $accessToken,
            Constants::ACCEPT => 'application/json',
            Constants::CONTENT_TYPE => 'application/json',
        ])->withOptions([Constants::VERIFY => false])->post($url, [
            Constants::MESSAGE => [
                Constants::SUBJECT => $subject,
                Constants::BODY => [
                    Constants::ContentType => Constants::TEXT,
                    Constants::CONTENT => $bodyContent,
                ],
                Constants::ToRecipients => array_map(function ($email) {
                    return [Constants::EmailAddress => [Constants::ADDRESS => $email]];
                }, $recipients),
            ],
        ]);

        return $response->json();
    }
}