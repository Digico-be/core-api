<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    public $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function via($notifiable)
    {
        return [BrevoChannel::class];
    }

    public function toBrevo($notifiable)
    {
        return [
            'sender' => [
                'email' => config('mail.from.address'),
                'name'  => config('mail.from.name'),
            ],
            'subject' => 'Réinitialisation de votre mot de passe',
            'body'    => "Vous avez demandé à réinitialiser votre mot de passe.\n\n"
                . "Cliquez sur le lien suivant pour réinitialiser votre mot de passe : {$this->url}\n\n"
                . "Si vous n'avez pas fait cette demande, aucune action n'est requise.",
        ];
    }
}
