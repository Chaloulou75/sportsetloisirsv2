<?php

namespace App\Http\Middleware;

use App\Models\User;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use Illuminate\Http\Request;
use App\Models\ProductReservation;
use App\Notifications\ReservationPaid;
use App\Notifications\ReservationPaidToAdmin;
use App\Notifications\ReservationPaidToStructure;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $panierProductsInSession = $request->session()->get('panierProducts');
        $panierProdCount = count($panierProductsInSession ?? []);
        $reservationsCount = 0;
        if($panierProdCount > 0 && $request->user()) {
            $reservationIds = array_column($panierProductsInSession, 'reservation_id');
            $reservationsCount = ProductReservation::whereIn('id', $reservationIds)->orWhere('user_id', $request->user()->id)->where('paid', false)->count();
        } elseif($panierProdCount > 0) {
            $reservationIds = array_column($panierProductsInSession, 'reservation_id');
            $reservationsCount = ProductReservation::whereIn('id', $reservationIds)->where('paid', false)->count();
        } elseif($request->user()) {
            $reservationsCount = ProductReservation::where('user_id', $request->user()->id)->where('paid', false)->count();
        }


        return array_merge(parent::share($request), [
            'appName' => config('app.name'),
            'auth' => [
                'user' => fn () => $request->user() ? array_merge(
                    $request->user()->load('structures:id,name,slug')->only('id', 'name', 'email', 'structures'),
                    ['unread_notifications_count' => $request->user()->unreadNotifications()->where('type', ReservationPaid::class)->orWhere('type', ReservationPaidToAdmin::class)->count()]
                ) : null,
            ],
            'structures_notifications' => fn () => $request->user() ? $request->user()->structures->mapWithKeys(function ($structure) {
                return [$structure->id => $structure->unreadNotifications()->where('type', ReservationPaidToStructure::class)->count()];
            }) : [],
            'user_can' => [
                'view_admin' => fn () => $request->user() ? $request->user()->can('viewAdmin', User::class) : false,
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy())->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                    'message' => $request->session()->get('message'),
                ];
            },
            'admin_notifications_count' => function () use ($request) {
                $user = $request->user();
                if ($user && $user->isAdmin()) {
                    return $user->unreadNotifications()->where('type', ReservationPaidToAdmin::class)->count();
                } else {
                    return null;
                }
            },
            'products_reservations_count' => fn () => $reservationsCount ?? null,
        ]);
    }
}
