<?php

// app/Providers/AppServiceProvider.php

namespace App\Providers;

use App\Events\RoleModified;
use App\Listeners\LogRoleActivity;
use App\Models\Category;
use App\Models\Competition;
use App\Models\Division;
use App\Models\Employee;
use App\Models\NavigationItem;
use App\Models\Permission;
use App\Models\Player;
use App\Models\Price;
use App\Models\Role;
use App\Models\Tournament;
use App\Models\User;
use App\Models\Venue;
use App\Policies\CategoryPolicy;
use App\Policies\CompetitionPolicy;
use App\Policies\DivisionPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\NavigationPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\PlayerPolicy;
use App\Policies\PricePolicy;
use App\Policies\RolePolicy;
use App\Policies\TournamentPolicy;
use App\Policies\UserPolicy;
use App\Policies\VenuePolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind custom models so Spatie uses our versions (with SoftDeletes)
        $this->app->bind(\Spatie\Permission\Models\Role::class, Role::class);
        $this->app->bind(\Spatie\Permission\Models\Permission::class, Permission::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(RoleModified::class, LogRoleActivity::class);

        // Register Policies
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
        Gate::policy(NavigationItem::class, NavigationPolicy::class);
        Gate::policy(Venue::class, VenuePolicy::class);
        Gate::policy(Tournament::class, TournamentPolicy::class);
        Gate::policy(Division::class, DivisionPolicy::class);
        Gate::policy(Competition::class, CompetitionPolicy::class);
        Gate::policy(Price::class, PricePolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Employee::class, EmployeePolicy::class);
        Gate::policy(Player::class, PlayerPolicy::class);

        // Super-admin bypasses all Gates — must be checked before any policy.
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super-admin')) {
                return true;
            }
        });
    }
}
