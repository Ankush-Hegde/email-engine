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

        $response = Http::asForm()->post($url, [
            Constants::CLIENT_ID => $this->clientId,
            Constants::CLIENT_SECRET => $this->clientSecret,
            Constants::CODE => $code,
            Constants::REDIRECT_URI => $this->redirectUri,
            Constants::GRANT_TYPE => Constants::AUTHORIZATION_CODE,
        ]);

        return $response->json();
    }
}