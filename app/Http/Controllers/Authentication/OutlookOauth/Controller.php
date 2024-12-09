<?php

namespace App\Http\Controllers\Authentication\OutlookOauth;

use App\Models\User;
use App\Services\Outlook\OutlookService;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $OutlookService;
    
    public function __construct()
    {
        $this->OutlookService = new OutlookService();
    }

    public function generate_url(Request $request)
    {        
        return $this->OutlookService->generateAuthUrl();
    }

    public function callback(Request $request)
    {
        $code = $request->get(Constants::CODE);

        if (!$code) {
            return response()->json([Constants::ERROR => 'Authorization code not found.'], 400);
        }

        $tokens = $this->OutlookService->getTokens($code);

        if (isset($tokens[Constants::ERROR])) {
            return response()->json([
                Constants::ERROR => $tokens[Constants::ERROR],
                Constants::ERROR_DESCRIPTION => $tokens[Constants::ERROR_DESCRIPTION],
            ], 400);
        }

        if (!isset($tokens[User::ACCESS_TOKEN])) {
            return response()->json([Constants::ERROR => 'Access token not found.'], 400);
        }

        $idToken = explode('.', $tokens[Constants::ID_TOKEN])[1];
        $userDetails = json_decode(base64_decode($idToken), true);

        $user = User::updateOrCreate(
            [User::EMAIL => $userDetails[User::EMAIL]],
            [
                User::NAME => $userDetails[User::NAME],
                User::ACCESS_TOKEN => $tokens[User::ACCESS_TOKEN],
                User::REFRESH_TOKEN => $tokens[User::REFRESH_TOKEN],
                User::TOKEN_EXPIRES_IN => now()->addSeconds($tokens[Constants::EXPIRES_IN]),
            ]
        );

        return response()->json([Constants::MESSAGE => 'User registered successfully', User::ENTITY_NAME => $user]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            User::NAME => 'required|string|max:255',
            User::EMAIL => 'required|email|unique:users,email',
        ]);

        $user = User::create([
            User::NAME => $validated[User::NAME],
            User::EMAIL => $validated[User::EMAIL],
        ]);

        return response()->json([
            CONSTANTS::MESSAGE => 'Account created successfully!',
            User::ENTITY_NAME => $user,
        ]);
    }
}