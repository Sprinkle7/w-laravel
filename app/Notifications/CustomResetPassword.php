<?php
namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;


class CustomResetPassword extends ResetPasswordNotification
{
    public $customIntroText;
    public $customOutroText;

    public function __construct($token, $customIntroText = null, $customOutroText = null)
    {
        parent::__construct($token);
        $this->customIntroText = $customIntroText;
        $this->customOutroText = $customOutroText;
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Benachrichtigung zum Zurücksetzen des Passworts')
            ->greeting('Hallo!')
            ->line('Sie erhalten diese E-Mail, weil wir eine Anfrage zum Zurücksetzen des Passworts für Ihr Konto erhalten haben.')
            ->action('Passwort zurücksetzen', $url)
            ->line('Wenn Sie keine Rücksetzung des Passworts beantragt haben, sind keine weiteren Schritte erforderlich.')
            ->salutation('Best Regards','Wirtschaft');
    }
}
