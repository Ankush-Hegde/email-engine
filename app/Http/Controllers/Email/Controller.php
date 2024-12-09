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
        $email = $request->input('email');

        $user = User::where(User::EMAIL, $email)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $refreshToken = $user->refresh_token;

        $tokens = $this->OutlookService->refreshAccessToken($refreshToken);

        if (isset($tokens['error'])) {
            return response()->json([
                'message' => 'Failed to refresh access token',
                'error' => $tokens['error_description'] ?? 'Unknown error',
            ], 400);
        }

        $user->update([
            User::ACCESS_TOKEN => $tokens['access_token'],
            User::REFRESH_TOKEN => $tokens['refresh_token'] ?? $refreshToken,
            User::TOKEN_EXPIRES_IN => now()->addSeconds($tokens['expires_in']),
        ]);

        $accessToken = $tokens['access_token'];

        $emails = $this->OutlookService->fetchEmails($accessToken);

        if (isset($emails['error'])) {
            return response()->json([
                'message' => 'Failed to fetch emails',
                'error' => $emails['error']['message'] ?? 'Unknown error',
            ], 400);
        }

        foreach ($emails['value'] as $email) {
            $data = [
                'user_id' => $user->id,
                'subject' => $email['subject'],
                'body' => $email['body']['content'],
                'from' => $email['from']['emailAddress']['address'],
                'to' => array_map(fn($r) => ['emailAddress' => $r['emailAddress']['address']], $email['toRecipients'] ?? []),
                'cc' => array_map(fn($r) => ['emailAddress' => $r['emailAddress']['address']], $email['ccRecipients'] ?? []),
                'bcc' => array_map(fn($r) => ['emailAddress' => $r['emailAddress']['address']], $email['bccRecipients'] ?? []),
                'is_read' => $email['isRead'],
                'received_date' => $email['receivedDateTime'],
                'sent_date' => $email['sentDateTime'],
            ];
        
            $this->elasticsearchService->indexDocument('emails', $email['id'], $data);
        }

        $searchQuery = [
            'query' => [
                'term' => [
                    'user_id' => $user->id,
                ],
            ],
        ];

        $emailResults = $this->elasticsearchService->search('emails', $searchQuery);
        
        return response()->json($emailResults['hits']['hits'], 200);
    }
}