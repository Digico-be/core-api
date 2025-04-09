<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Handle the incoming request to send a password reset link.
     */
    public function sendResetLink(Request $request)
    {
        // Validation de l'email
        $request->validate([
            'email' => 'required|email',
        ]);

        // Envoi du lien de réinitialisation
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Un lien de réinitialisation a été envoyé.'], 200);
        }

        return response()->json(['message' => 'Erreur lors de l\'envoi du lien.'], 400);
    }
}
