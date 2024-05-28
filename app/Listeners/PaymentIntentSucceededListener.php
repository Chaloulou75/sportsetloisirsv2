<?php

namespace App\Listeners;

use App\Models\User;
use Stripe\StripeClient;
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

                // $stripe = new StripeClient(config('services.stripe.secret'));
                // // Create the invoice
                // $invoice = $stripe->invoices->create([
                //     'customer' => $customer->stripe_id,
                //     'auto_advance' => true, // Automatically finalize and attempt payment on the invoice
                //     'collection_method' => 'charge_automatically',
                // ]);

                // $stripe->invoiceItems->create([
                //     'customer' => $customer->stripe_id,
                //     'invoice' => $invoice->id,
                //     'amount' => $totalPrice * 100, // Amount should be in cents
                //     'currency' => 'eur',
                //     'description' => "Reservation ID: {$reservation->id}",
                // ]);

                // $finalInvoice = $stripe->invoices->finalizeInvoice($invoice->id);

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
