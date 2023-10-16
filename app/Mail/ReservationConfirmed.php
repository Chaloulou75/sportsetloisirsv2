<?php

namespace App\Mail;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Structure;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\StructureTarif;
use App\Models\StructureProduit;
use App\Models\StructureActivite;
use App\Models\StructurePlanning;
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
        protected StructureActivite $activite,
        protected StructureProduit $produit,
        protected StructurePlanning $planning,
        protected StructureTarif $tarif,
        protected ProductReservation $reservation,
        protected User $user,
    ) {}

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

        $planningStart = Carbon::parse($this->planning->start);
        $planningEnd = Carbon::parse($this->planning->end);
        $formattedStart = $planningStart->locale('fr_FR')->isoFormat('dddd D MMMM YYYY [à] H[h]mm');
        $formattedEnd = $planningEnd->locale('fr_FR')->isoFormat('dddd D MMMM YYYY [à] H[h]mm');

        return new Content(
            markdown: 'emails.reservations.confirmed',
            with: [
                'url' => route('structures.activites.show', ['activite' => $this->activite->id]),
                'activiteName' => $this->activite->titre,
                'tarifAmount' => $this->tarif->amount,
                'planningStart' => $formattedStart,
                'planningEnd' =>  $formattedEnd,
                'userName' => $this->user->name,
                'userEmail' => $this->user->email,
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
