<?php

namespace App\Http\Controllers;

use App\libs\Brevo\MailServiceInterface;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public MailServiceInterface $mail;

    public function __construct(MailServiceInterface $mail)
    {
        $this->mail = $mail;
    }

    public function send(Request $request)
    {
        // Validation des données
        $request->validate([
            "to" => "required|email",
            "subject" => "required|string",
            "content" => "required|string",
        ], [
            "to.required" => "L'adresse du destinataire est requise.",
            "subject.required" => "Le sujet est requis.",
            "content.required" => "Le contenu de l'email est requis."
        ]);

        // Envoi du mail
        $this->mail->from(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
            ->to($request->to)
            ->subject($request->subject)
            ->content($request->content)
            ->send();

        return response()->json(['message' => 'Email envoyé avec succès'], 200);
    }
}
