<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\Structure;
use App\Policies\StructurePolicy;
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
            return ($user->id === $structure->user_id) || ($user->email === 'c.jeandey@gmail.com') || ($user->email === 'tonio20@hotmail.fr');
        });
        Gate::define('destroy-structure', function (User $user, Structure $structure) {
            return ($user->id === $structure->user_id) || ($user->email === 'c.jeandey@gmail.com') || ($user->email === 'tonio20@hotmail.fr');
        });
    }
}
