<?php

// app/Providers/AppServiceProvider.php

namespace App\Providers;

use App\Events\RoleModified;
use App\Listeners\LogRoleActivity;
use App\Models\Category;
use App\Models\Competition;
use App\Models\Division;
use App\Models\Employee;
use App\Models\Game;
use App\Models\GameRole;
use App\Models\Media;
use App\Models\NavigationItem;
use App\Models\Payment;
use App\Models\Payroll;
use App\Models\Permission;
use App\Models\Player;
use App\Models\PlayerSanction;
use App\Models\Price;
use App\Models\Role;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use App\Models\Venue;
use App\Policies\AuditPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CompetitionPolicy;
use App\Policies\DivisionPolicy;
use App\Policies\EmployeePolicy;
use App\Policies\GamePolicy;
use App\Policies\GameRolePolicy;
use App\Policies\MediaPolicy;
use App\Policies\NavigationPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\PayrollPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\PlayerPolicy;
use App\Policies\PlayerSanctionPolicy;
use App\Policies\PricePolicy;
use App\Policies\ReportPolicy;
use App\Policies\RolePolicy;
use App\Policies\TeamPolicy;
use App\Policies\TournamentPolicy;
use App\Policies\UserPolicy;
use App\Policies\VenuePolicy;
use App\Services\NavigationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View as ViewFacade;
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

        ViewFacade::composer('layouts.navigation', function ($view) {
            $user = Auth::user();
            $items = $user ? app(NavigationService::class)->getVisibleTree($user) : collect();

            $view->with('navigationItems', $items);
        });

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
        Gate::policy(Game::class, GamePolicy::class);
        Gate::policy(GameRole::class, GameRolePolicy::class);
        Gate::policy(Payment::class, PaymentPolicy::class);
        Gate::policy(Payroll::class, PayrollPolicy::class);
        Gate::policy(Media::class, MediaPolicy::class);
        Gate::policy(Player::class, PlayerPolicy::class);
        Gate::policy(PlayerSanction::class, PlayerSanctionPolicy::class);
        Gate::policy(Team::class, TeamPolicy::class);

        // Reports are not model-backed — register named gates.
        Gate::define('reports.view', [ReportPolicy::class, 'view']);
        Gate::define('reports.export', [ReportPolicy::class, 'export']);

        // Audit is not model-backed — register named gates.
        Gate::define('audit.view', [AuditPolicy::class, 'view']);
        Gate::define('audit.export', [AuditPolicy::class, 'export']);

        // Super-admin bypasses all Gates — must be checked before any policy.
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super-admin')) {
                return true;
            }
        });
    }
}
