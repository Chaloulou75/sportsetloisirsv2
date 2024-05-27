<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\ProductReservation;
use App\Notifications\ReservationPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\WebhookClient\Models\WebhookCall;
use App\Notifications\ReservationPaidToAdmin;
use App\Notifications\ReservationPaidToStructure;

class PaymentIntentSucceededListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WebhookCall $webhookCall): void
    {
        $payload = $webhookCall->payload;

        $sessionStripe = $payload['data']['object'];

        $reservations = ProductReservation::withRelations()
            ->where('stripe_session_id', $sessionStripe['id'])
            ->where('paid', false)
            ->get();

        if ($reservations->isNotEmpty()) {
            // Update all matching reservations at once
            foreach ($reservations as $reservation) {
                $user = $reservation->user;
                $payment_method = !empty($sessionStripe['payment_method_types']) ? $sessionStripe['payment_method_types'][0] : null;

                $reservation->update([
                    'paid' => true,
                    'paiement_method' => $payment_method,
                    'paiement_datetime' => now(),
                ]);
                $user->notify(new ReservationPaid($reservation, $sessionStripe));

                $reservation->structure->notify(new ReservationPaidToStructure($reservation, $sessionStripe));

                $admins = User::whereHas('roles', function ($query) {
                    $query->where('name', 'admin');
                })->get();
                if ($admins->isNotEmpty()) {
                    foreach ($admins as $admin) {
                        $admin->notify(new ReservationPaidToAdmin($reservation, $sessionStripe));
                    }
                }
            }
        }
    }
}
