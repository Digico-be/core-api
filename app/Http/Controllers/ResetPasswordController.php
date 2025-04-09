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
            ], [
                'token.required'    => 'Le token est manquant.',
                'email.required'    => 'L\'adresse email est requise.',
                'email.email'       => 'Le format de l\'adresse email est invalide.',
                'password.required' => 'Le mot de passe est obligatoire.',
                'password.confirmed'=> 'La confirmation du mot de passe ne correspond pas.',
                'password.min'      => 'Le mot de passe doit comporter au moins 8 caractères.',
            ]);
        } catch (ValidationException $e) {
            Log::error('Validation failed for password reset', $e->errors());
            return response()->json([
                'message' => 'Erreur de validation',
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
            return response()->json([
                'message' => __('Une erreur est survenue lors de la réinitialisation du mot de passe.')
            ], 400);
        }
    }


}
