<?php

namespace App\Notifications;

use App\libs\Brevo\MailServiceInterface;
use Illuminate\Notifications\Notification;

class BrevoChannel
{
    /**
     * Envoie la notification via Brevo.
     *
     * @param mixed $notifiable L'entité notifiable
     * @param \Illuminate\Notifications\Notification $notification La notification à envoyer
     */
    public function send($notifiable, Notification $notification)
    {
        // Le canal s'attend à ce que la notification ait une méthode toBrevo()
        if (! method_exists($notification, 'toBrevo')) {
            return;
        }

        $message = $notification->toBrevo($notifiable);

        // Récupérer l'instance du service Brevo depuis le conteneur
        /** @var MailServiceInterface $mailService */
        $mailService = app(MailServiceInterface::class);

        // Configurer et envoyer l’email via Brevo
        $mailService->from($message['sender']['email'], $message['sender']['name'])
            ->to($notifiable->email)
            ->subject($message['subject'])
            ->content($message['body'])
            ->send();
    }
}
