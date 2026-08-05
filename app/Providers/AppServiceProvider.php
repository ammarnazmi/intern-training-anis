<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Onpay\Core\Auth\Notifications\ResetPassword;
use Onpay\Core\Auth\Notifications\VerifyEmail;
use Onpay\Core\Configurator;
use Onpay\Core\Http\Middleware\TrackLastOnline;
use Onpay\Core\Listeners\TrackLastLogin;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Use extended paginators
        Configurator::useExtendedPaginators();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Bootstrap 5
        Configurator::useBootstrap5();

        // Register event listeners
        Event::listen(Login::class, TrackLastLogin::class);

        // Configure after callbacks
        $afterCallback = static function (AuthUser $user) {
            $identifier = str($user::class)->classBasename()->snake();
            $store = cache()->memo();

            $store->put(
                $identifier->append('-', $user->getKey())->value(),
                $user->withoutRelations(),
                config($identifier->plural()->wrap('auth.providers.', '.ttl')->value())
            );

            $store->put($identifier->append('_last_online_time')->value(), time(), 3600);
        };

        TrackLastLogin::after($afterCallback);
        TrackLastOnline::after($afterCallback);

        // Force HTTPS scheme if it is configured
        if (config('app.url_scheme') === 'https') {
            URL::forceScheme('https');
        }

        // Configure rate limiters
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(60)->by($request->user()?->id ?: $request->ip()));

        // Custom polymorphic types
        Relation::enforceMorphMap([
            'admin' => Admin::class,
            'user' => User::class,
        ]);

        // Set callbacks for password reset and email verification
        ResetPassword::createUrlUsing(fn ($notifiable, $token) => URL::route(
            strtolower(class_basename($notifiable)) . '.password.reset',
            [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]
        ));

        VerifyEmail::createUrlUsing(fn ($notifiable) => URL::temporarySignedRoute(
            strtolower(class_basename($notifiable)) . '.verification.verify',
            now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        ));
    }
}
