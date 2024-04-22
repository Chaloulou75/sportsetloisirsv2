<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\Activite;
use App\Models\Structure;
use App\Models\StructureActivite;
use App\Policies\StructurePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Structure::class => StructurePolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage())
                ->subject('Verification de votre adresse Email')
                ->line('Veuillez cliquer sur le bouton ci-dessous pour vérifier votre adresse e-mail :')
                ->action('Verifier votre adresse Email', $url);
        });

        $this->registerPolicies();

        Gate::define('update-structure', function (User $user, Structure $structure) {
            return ($user->id === $structure->user_id) || $user->isAdmin();
        });
        Gate::define('destroy-structure', function (User $user, Structure $structure) {
            return ($user->id === $structure->user_id) || $user->isAdmin();
        });

        Gate::define('update-activite', function (User $user, StructureActivite $activite) {
            return ($user->id === $activite->user_id) || $user->isAdmin();
        });
        Gate::define('destroy-activite', function (User $user, StructureActivite $activite) {
            return ($user->id === $activite->user_id) || $user->isAdmin();
        });

        Gate::define('viewPulse', function (User $user) {
            return $user->isAdmin();
        });

    }
}
