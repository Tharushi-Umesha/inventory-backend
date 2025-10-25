<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware groups.
     */
    protected $middlewareGroups = [
        'api' => [
            // Sanctum middleware for API token authentication
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,

            // Route model binding
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     * 
     * Changed from $routeMiddleware to $middlewareAliases for Laravel 11+
     */
    protected $middlewareAliases = [
        // Built-in authentication middleware
        'auth' => \App\Http\Middleware\Authenticate::class,

        // ✅ Custom middleware for role-based routes
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ];
}
