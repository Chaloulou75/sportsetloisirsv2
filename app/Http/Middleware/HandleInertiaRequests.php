<?php

namespace App\Http\Middleware;

use App\Models\User;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use Illuminate\Http\Request;
use App\Models\ProductReservation;

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
        $user = $request->user();
        $sessionId = $request->session()->getId();
        $reservations = [];
        if ($user || $sessionId) {
            $query = ProductReservation::withRelations()->withCount('plannings');
            if (isset($user) && $sessionId) {
                $reservations = ProductReservation::withRelations()->withCount('plannings')->where('user_id', $user->id)->orWhere('session_id', $sessionId)->get();
            } elseif ($sessionId) {
                $reservations = ProductReservation::withRelations()->withCount('plannings')->where('session_id', $sessionId)->get();
            } elseif ($user) {
                $reservations = ProductReservation::withRelations()->withCount('plannings')->where('user_id', $user->id)->get();
            }
        }

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => fn () => $user ? $user->load('structures:id,name,slug')->only('id', 'name', 'email', 'structures') : null,
            ],
            'user_can' => [
                'view_admin' => fn () => $user ? $user->can('viewAdmin', User::class) : false,
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
            'productsReservations' => $reservations ?? null,
        ]);
    }
}
