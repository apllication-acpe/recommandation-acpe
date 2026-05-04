<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageReceived extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Nouveau message de ' . $this->message->sender->nom_complet)
                    ->greeting('Bonjour ' . $notifiable->nom_complet . ',')
                    ->line('Vous avez reçu un nouveau message sur la plateforme ACPE-recommandation.')
                    ->line('**Objet :** ' . $this->message->objet)
                    ->action('Consulter le message', route('messagerie.show', $this->message))
                    ->line('Merci d\'utiliser notre plateforme !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id_message' => $this->message->id_message,
            'sender_name' => $this->message->sender->nom_complet,
            'objet' => $this->message->objet,
        ];
    }
}
