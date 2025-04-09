<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    /**
     * Affiche le formulaire de réinitialisation du mot de passe.
     *
     * Cette méthode reçoit le token via l'URL et transmet les données à la vue.
     */
    public function showResetForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        return response()->json([
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Traite la soumission du formulaire de réinitialisation.
     */
    public function reset(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'token'    => 'required',
                'email'    => 'required|email',
                'password' => 'required|confirmed|min:8',
            ]);
        } catch (ValidationException $e) {
            // Logguez les erreurs pour le debug
            Log::error('Validation failed for password reset', $e->errors());
            // Retournez les erreurs
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Mot de passe réinitialisé avec succès.'
            ], 200);
        } else {
            // Vous pouvez également détailler l'erreur si souhaité.
            return response()->json([
                'message' => __($status)
            ], 400);
        }
    }

}
