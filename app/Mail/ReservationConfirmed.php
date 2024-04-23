<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\ProductReservation;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationConfirmed extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected ProductReservation $reservation
    ) {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('support@sports-et-loisirs.fr', 'Antoine'),
            subject: 'Reservation Confirmée',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // $planningStart = Carbon::parse($this->planning->start);
        // $planningEnd = Carbon::parse($this->planning->end);
        // $formattedStart = $planningStart->locale('fr_FR')->isoFormat('dddd D MMMM YYYY [à] H[h]mm');
        // $formattedEnd = $planningEnd->locale('fr_FR')->isoFormat('dddd D MMMM YYYY [à] H[h]mm');

        return new Content(
            markdown: 'emails.reservations.confirmed',
            with: [
                'url' => route('structures.activites.show', [
                    'activite' => $this->reservation->activite->id,
                    'slug' => $this->reservation->activite->slug_title
                ]),
                'activiteName' => $this->reservation->activite_title,
                'tarifAmount' => $this->reservation->tarif_amount,
                'quantity' => $this->reservation->quantity,
                // 'planningStart' => $formattedStart,
                // 'planningEnd' =>  $formattedEnd,
                'userName' => $this->reservation->user->name,
                'userEmail' => $this->reservation->user->email,
                'reservationCode' => $this->reservation->code,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
