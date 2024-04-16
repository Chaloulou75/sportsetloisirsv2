<?php

namespace App\Notifications;

use App\Models\ProductReservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationPaid extends Notification implements ShouldQueue
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

        $invoiceUrl = $this->sessionStripe->hosted_invoice_url;

        return (new MailMessage())
            ->subject('Confirmation du paiement de votre réservation.')
            ->greeting('Merci pour votre réservation ' . $this->reservation->activite_title)
            ->line('Details de votre réservation:')
            ->line('Reservation numéro: ' . $this->reservation->id)
            ->line('Montant unitaire: ' . $this->reservation->tarif_amount)
            ->line('Quantité: ' . $this->reservation->quantity)
            ->line('Methode de paiement: ' . $this->reservation->paiement_method)
            ->action('Voir votre facture liée', $invoiceUrl)
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
