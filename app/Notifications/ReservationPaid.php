<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\ProductReservation;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReservationPaid extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public ProductReservation $reservation,
        public $sessionStripe,
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
        ->subject('Confirmation du paiement de votre réservation.')
        ->greeting('Merci pour votre réservation ' . $this->reservation->activite_title)
        ->line('Details de votre réservation:')
        ->line('Reservation numéro: ' . $this->reservation->id)
        ->line('Methode de paiement: ' . $this->reservation->paiement_method)
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
            'reservation_id' => $this->reservation->id,
            'structure_id' => $this->reservation->structure_id,
            'activite_id' => $this->reservation->activite_id,
            'produit_id' => $this->reservation->produit_id,
            'unit_amount' => $this->reservation->tarif_amount,
            'quantity' => $this->reservation->quantity,
            'stripe_session_id' => $this->reservation->stripe_session_id,
        ];
    }
}
