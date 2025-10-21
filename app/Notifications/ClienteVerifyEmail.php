<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class ClienteVerifyEmail extends Notification 
{
    use Queueable;

    public function via($notifiable)
    {
        //Obligatorio: define por qué canal(es) se envía
        return ['mail'];
    }

    protected function verificacionURL($notifiable)
    {
        return URL::temporarySignedRoute(
            'cliente.verification.verify', // tu ruta de cliente
            now()->addMinutes(Config::get('auth.verification.expire', 30)),
            [
                'id'   => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    //Es como se vera el correo de verificacion.
    public function toMail($notifiable)
    {
        $url = $this->verificacionURL($notifiable);

        return (new MailMessage)
            ->subject('Verifica tu email')
            ->greeting('¡Hola!')
            ->line('Confirma tu correo para acceder a tu espacio de cliente.')
            ->action('Verificar correo', $url)
            ->line('El enlace expira en 30 minutos.');
    }
}
