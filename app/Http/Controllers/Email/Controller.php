<?php

namespace App\Http\Controllers\Email;

use App\Models\User;
use App\Services\Outlook\OutlookService;
use App\Services\ElasticsearchService\ElasticsearchService;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $OutlookService;
    protected $elasticsearchService;
    
    public function __construct()
    {
        $this->OutlookService = new OutlookService();
        $this->elasticsearchService = new ElasticsearchService();
    }

    public function fetchEmails(Request $request)
    {
        $email = $request->input(User::EMAIL);

        $user = User::where(User::EMAIL, $email)->first();
        if (!$user) {
            return response()->json([Constants::MESSAGE => 'User not found'], 404);
        }

        $refreshToken = $user->refresh_token;

        $tokens = $this->OutlookService->refreshAccessToken($refreshToken);

        if (isset($tokens[Constants::ERROR])) {
            return response()->json([
                Constants::MESSAGE => 'Failed to refresh access token',
                Constants::ERROR => $tokens[Constants::ERROR_DESCRIPTION] ?? 'Unknown error',
            ], 400);
        }

        $user->update([
            User::ACCESS_TOKEN => $tokens[User::ACCESS_TOKEN],
            User::REFRESH_TOKEN => $tokens[User::REFRESH_TOKEN] ?? $refreshToken,
            User::TOKEN_EXPIRES_IN => now()->addSeconds($tokens[Constants::EXPIRES_IN]),
        ]);

        $accessToken = $tokens[User::ACCESS_TOKEN];

        $emails = $this->OutlookService->fetchEmails($accessToken);

        if (isset($emails[Constants::ERROR])) {
            return response()->json([
                Constants::MESSAGE => 'Failed to fetch emails',
                Constants::ERROR => $emails[Constants::ERROR][Constants::MESSAGE] ?? 'Unknown error',
            ], 400);
        }

        foreach ($emails[Constants::VALUE] as $email) {
            $data = [
                Constants::USER_ID => $user->id,
                Constants::SUBJECT => $email[Constants::SUBJECT],
                Constants::BODY => $email[Constants::BODY][Constants::CONTENT],
                Constants::FORM => $email[Constants::FORM][Constants::EmailAddress][Constants::ADDRESS],
                Constants::TO => array_map(fn($r) => [Constants::EmailAddress => $r[Constants::EmailAddress][Constants::ADDRESS]], $email[Constants::ToRecipients] ?? []),
                Constants::CC => array_map(fn($r) => [Constants::EmailAddress => $r[Constants::EmailAddress][Constants::ADDRESS]], $email[Constants::CcRecipients] ?? []),
                Constants::BCC => array_map(fn($r) => [Constants::EmailAddress => $r[Constants::EmailAddress][Constants::ADDRESS]], $email[Constants::BccRecipients] ?? []),
                Constants::IS_READ => $email[Constants::IsRead],
                Constants::RECEVIED_DATA => $email[Constants::ReceivedDateTime],
                Constants::SENT_DATA => $email[Constants::SentDateTime],
            ];
        
            $this->elasticsearchService->indexDocument(Constants::EMAILS, $email[Constants::ID], $data);
        }

        $searchQuery = [
            Constants::QUERRY => [
                Constants::TERM => [
                    Constants::USER_ID => $user->id,
                ],
            ],
        ];

        $emailResults = $this->elasticsearchService->search(Constants::EMAILS, $searchQuery);
        
        return response()->json($emailResults[Constants::HITS][Constants::HITS], 200);
    }

    public function sendEmail(Request $request)
    {
        $email = $request->input(User::EMAIL);
        $subject = $request->input(Constants::SUBJECT);
        $bodyContent = $request->input(Constants::BODY);
        $recipients = $request->input(Constants::ToRecipients);

        if (!$email || !$subject || !$bodyContent || !$recipients) {
            return response()->json([
                Constants::MESSAGE => 'Missing required fields: email, subject, body, or recipients.',
            ], 400);
        }

        $user = User::where(User::EMAIL, $email)->first();
        if (!$user) {
            return response()->json([Constants::MESSAGE => 'User not found'], 404);
        }

        $refreshToken = $user->refresh_token;

        $tokens = $this->OutlookService->refreshAccessToken($refreshToken);

        if (isset($tokens[Constants::ERROR])) {
            return response()->json([
                Constants::MESSAGE => 'Failed to refresh access token',
                Constants::ERROR => $tokens[Constants::ERROR_DESCRIPTION] ?? 'Unknown error',
            ], 400);
        }

        $user->update([
            User::ACCESS_TOKEN => $tokens[User::ACCESS_TOKEN],
            User::REFRESH_TOKEN => $tokens[User::REFRESH_TOKEN] ?? $refreshToken,
            User::TOKEN_EXPIRES_IN => now()->addSeconds($tokens[Constants::EXPIRES_IN]),
        ]);

        $accessToken = $tokens[User::ACCESS_TOKEN];

        $response = $this->OutlookService->sendEmail($accessToken, $subject, $bodyContent, $recipients);

        if (isset($response[Constants::ERROR])) {
            return response()->json([
                Constants::MESSAGE => 'Failed to send email',
                Constants::ERROR => $response[Constants::ERROR][Constants::MESSAGE] ?? 'Unknown error',
            ], 400);
        }

        return response()->json([Constants::MESSAGE => 'Email sent successfully'], 200);
    }
}