<?php

namespace App\libs\Brevo;


use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Configuration;
use Brevo\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;


class Brevo implements MailServiceInterface{
    protected TransactionalEmailsApi $apiInstance;
    protected array $to = [];
    protected array $cc = [];
    protected string $subject = 'No Subject';
    protected string $htmlContent = '';
    protected array $attachments = [];
    protected array $sender = [];

    protected array $bcc = [];

    public function __construct()
    {
        // Configuration de l'API avec la clé API de Brevo
        try {
            $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', env('BREVO_API_KEY'));
            $this->apiInstance = new TransactionalEmailsApi(new Client(), $config);

            $this->sender = [
                'email' => env('MAIL_FROM_ADDRESS'),
                'name' => env('MAIL_FROM_NAME'),
            ];
        } catch (Exception $e) {
            Log::error('Brevo API Initialization Error: ' . $e->getMessage());
            throw new Exception('Error initializing Brevo API: ' . $e->getMessage());
        }
    }


    public function to(string $email): self
    {
        $this->to[] = ['email' => $email];
        return $this;
    }

    public function cc($emails): self
    {
        if(!$emails){
            return $this;
        }

        $emails = is_array($emails) ? $emails : [$emails];
        foreach ($emails as $email) {
            $this->cc[] = ['email' => $email];
        }
        return $this;
    }

    public function subject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    public function view(string $view, array $data = []): self
    {
        $this->htmlContent = view($view, $data)->render();
        return $this;
    }

    public function content(string $content): self
    {
        $this->htmlContent = $content;
        return $this;
    }

    public function from(string $email, ?string $name = null): self
    {
        $this->sender = [
            'email' => $email,
            'name' => $name ?? $email,
        ];
        return $this;
    }

    public function attachments(array $attachments): self
    {
        foreach ($attachments as $attachment) {
            $this->attachments[] = [
                'name' => $attachment['filename'],
                'content' => base64_encode($attachment['output']),
            ];
        }
        return $this;
    }
    public function bcc($emails): self
    {
        if(!$emails){
            return $this;
        }

        $emails = is_array($emails) ? $emails : [$emails];
        foreach ($emails as $email) {
            $this->bcc[] = ['email' => $email];
        }
        return $this;
    }
    public function send(): bool
    {
        try {
            $data = [
                'sender' => $this->sender,
                'to' => $this->to,
                'subject' => $this->subject,
                'htmlContent' => $this->htmlContent,
            ];

            if($this->cc){
                $data["cc"] = $this->cc;
            }

            if($this->attachments){
                $data['attachment'] = $this->attachments;
            }

            if($this->bcc){
                $data["bcc"] = $this->bcc;
            }

            $email = new SendSmtpEmail($data);

            $this->apiInstance->sendTransacEmail($email);
            return true;
        } catch (\Exception $e) {
            Log::error('Brevo Send Error: ' . $e->getMessage());
            return false;
        }
    }
}
