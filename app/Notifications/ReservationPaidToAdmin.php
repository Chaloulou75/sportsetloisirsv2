<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\ProductReservation;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReservationPaidToAdmin extends Notification implements ShouldQueue
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

        return ['mail', 'database'];

    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {

        $urlGestion = route('structures.gestion.index', $this->reservation->structure);

        return (new MailMessage())
                    ->subject('Une nouvelle réservation a été réglée.')
                    ->greeting('Vous avez reçu le paiement d\'une réservation pour votre activité: ' . $this->reservation->activite_title . ' de la structure: ' .$this->reservation->structure->name)
                    ->line('Réservation: ' . $this->reservation->id)
                    ->line('Montant unitaire: ' . $this->reservation->tarif_amount)
                    ->line('Quantité: ' . $this->reservation->quantity)
                    ->line('Methode de paiement: ' . $this->reservation->paiement_method)
                    ->action('Voir la réservation pour la structure', $urlGestion)
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
