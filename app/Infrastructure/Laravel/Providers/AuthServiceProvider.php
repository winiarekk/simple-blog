<?php

namespace App\Infrastructure\Laravel\Providers;

use App\Domain\User\Entities\User;
use App\Domain\Blog\Entities\Post;
use App\Infrastructure\Laravel\Policies\UserPolicy;
use App\Infrastructure\Laravel\Policies\BlogPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Post::class => BlogPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Auth::provider('repository', function ($app, array $config) {
            return new RepositoryUserProvider(
                $config['user_repository'],
                $config['role_repository'],
                $config['user_access_factory'],
                $app['hash']
            );
        });
    }
}
