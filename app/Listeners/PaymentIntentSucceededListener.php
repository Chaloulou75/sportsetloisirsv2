<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\ProductReservation;
use App\Notifications\ReservationPaid;
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
            foreach ($reservations as $reservation) {
                $user = $reservation->user;
                $customer = $user->customer;

                $payment_method = !empty($sessionStripe['payment_method_types']) ? $sessionStripe['payment_method_types'][0] : null;

                $totalPrice = 0;

                if($reservation->plannings_count > 0) {
                    foreach($reservation->plannings as $planning) {
                        $price = $planning->pivot->quantity * $reservation->tarif_amount;
                        $totalPrice += $price;
                    }

                } else {
                    $price = $reservation->tarif_amount * $reservation->quantity;
                    $totalPrice += $price;
                }

                $reservation->update([
                    'paid' => true,
                    'paiement_method' => $payment_method,
                    'paiement_datetime' => now(),
                ]);

                $reservation->structure->notify(new ReservationPaidToStructure($reservation, $sessionStripe));
                $user->notify(new ReservationPaid($reservation, $sessionStripe));

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
