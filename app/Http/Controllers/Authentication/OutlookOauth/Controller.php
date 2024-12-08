<?php

namespace App\Http\Controllers\Authentication\OutlookOauth;

use App\Models\User;
use App\Services\Outlook\OutlookService;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function generate_url(Request $request)
    {
        $OutlookService = new OutlookService();
        
        return $OutlookService->generateAuthUrl();
    }

    public function callback(Request $request)
    {
        // try {
        //     $outlookUser = Socialite::driver(CONSTANTS::MICROSOFT)->user();

        //     // Check if the local user exists
        //     $user = User::where(User::EMAIL, $outlookUser->getEmail())->first();

        //     if (!$user) {
        //         return response()->json([CONSTANTS::MESSAGE => 'User not found. Please register first.'], 404);
        //     }

        //     // Link the Outlook account
        //     $user->update([
        //         User::MICROSOFT_ID => $outlookUser->getId(),
        //         User::ACCESS_TOKEN => $outlookUser->token,
        //         User::REFRESH_TOKEN => $outlookUser->refreshToken,
        //         User::TOKEN_EXPIRES_IN => $outlookUser->expiresIn,
        //     ]);

        //     return response()->json([
        //         CONSTANTS::MESSAGE => 'Outlook account linked successfully!',
        //         User::ENTITY_NAME => $user,
        //     ]);
        // } catch (\Exception $e) {
        //     return response()->json(['error' => 'Authentication failed.', 'details' => $e->getMessage()], 500);
        // }
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            User::NAME => 'required|string|max:255',
            User::EMAIL => 'required|email|unique:users,email',
            User::PASSWORD => 'required|string|min:8',
        ]);

        $user = User::create([
            User::NAME => $validated[User::NAME],
            User::EMAIL => $validated[User::EMAIL],
            User::PASSWORD => bcrypt($validated[User::PASSWORD]),
        ]);

        return response()->json([
            CONSTANTS::MESSAGE => 'Account created successfully!',
            User::ENTITY_NAME => $user,
        ]);
    }
}