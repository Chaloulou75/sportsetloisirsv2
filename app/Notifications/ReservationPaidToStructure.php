<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\ProductReservation;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReservationPaidToStructure extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public ProductReservation $reservation,
        public $sessionStripe
    ) {
        //
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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        return (new MailMessage())
                    ->subject('Vous avez reçu une nouvelle réservation!')
                    ->greeting('Félicitation '. $this->reservation->structure->name .'!')
                    ->line('Vous avez reçu un paiement pour une réservation pour votre activité: ' . $this->reservation->activite_title)
                    ->line('Réservation: ' . $this->reservation->id)
                    ->line('Montant unitaire: ' . $this->reservation->tarif_amount)
                    ->line('Quantité: ' . $this->reservation->quantity)
                    ->line('Methode de paiement: ' . $this->reservation->paiement_method)
                    // ->action('Voir la réservation dans votre dashboard')
                    ->line('Si vous avez des questions, contactez nous.');

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
