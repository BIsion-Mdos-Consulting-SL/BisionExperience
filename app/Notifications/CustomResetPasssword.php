<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasssword extends Notification
{
    use Queueable;

    public $token; //Porpieda puiblica para que resiba esa varibale.

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification. (Mensaje que se enviara y visualizara).
     */
    public function toMail(object $notifiable): MailMessage
    {
        $minutes = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

        return (new MailMessage)
            ->greeting('¡Hola!')
            ->subject('Notificación de restablecimiento de contraseña')
            ->line('Recibes este correo porque se solicitó un restablecimiento de contraseña para tu cuenta.')
            ->action('Restablecer contraseña', route('password.reset', ['token' => $this->token]))
            ->line(__('Este enlace para restablecer la contraseña caducará en :count minutos.', ['count' => $minutes]))
            ->line('Si no solicitaste un restablecimiento de contraseña, no es necesario realizar ninguna acción.')
            ->salutation('Gracias de antemano.');
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
