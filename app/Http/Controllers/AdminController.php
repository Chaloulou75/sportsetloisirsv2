<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Notifications\ReservationPaidToAdmin;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);

        $notifications = $user->unreadNotifications()->where('type', ReservationPaidToAdmin::class)->get();

        return Inertia::render('Admin/Index', [
            'user_can' => [
                'view_admin' => $user->can('viewAdmin', User::class),
            ],
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead($notification)
    {
        $user = auth()->user();
        $this->authorize('viewAdmin', $user);
        $notif = $user->unreadnotifications()->find($notification);
        if($notif) {
            $notif->markAsRead();
        }

        return to_route('admin.index')->with('message', 'Notification marquée comme lue');
    }
}
