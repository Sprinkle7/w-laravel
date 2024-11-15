<?php
namespace App\Notifications;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;


class CustomResetPassword extends ResetPasswordNotification 
{
    public $customIntroText;
    public $customOutroText;
    public $name;

    public function __construct($token, $name, $customIntroText = null, $customOutroText = null)
    {
        parent::__construct($token);
        $this->customIntroText = $customIntroText;
        $this->customOutroText = $customOutroText;
        $this->name = $name;
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
        ->subject('Passwort zurücksetzen')
        ->greeting('Hallo ' . $this->name . ',')
        ->line($this->customIntroText ?? 'Wir haben eine Anfrage zum Zurücksetzen Ihres Passworts erhalten.')
        ->action('Passwort zurücksetzen', $url)
        ->line($this->customOutroText ?? 'Wenn Sie keine Passwortzurücksetzung angefordert haben, ignorieren Sie diese E-Mail.');
    }
}