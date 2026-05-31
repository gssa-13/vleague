<?php

use App\Http\Controllers\Audit\AuditController;
use App\Http\Controllers\Competitions\CompetitionController;
use App\Http\Controllers\Divisions\DivisionController;
use App\Http\Controllers\Employees\EmployeeController;
use App\Http\Controllers\Finance\PaymentController;
use App\Http\Controllers\GameRoles\GameRoleAssignmentController;
use App\Http\Controllers\GameRoles\GameRoleController;
use App\Http\Controllers\IdentityAccess\PermissionController;
use App\Http\Controllers\IdentityAccess\RoleController;
use App\Http\Controllers\IdentityAccess\UserController;
use App\Http\Controllers\Localization\LocaleController;
use App\Http\Controllers\Media\MediaController;
use App\Http\Controllers\Navigation\NavigationItemController;
use App\Http\Controllers\Payroll\PayrollController;
use App\Http\Controllers\Players\PlayerController;
use App\Http\Controllers\PlayerSanctions\PlayerSanctionController;
use App\Http\Controllers\Prices\CategoryController;
use App\Http\Controllers\Prices\PriceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Reports\FinancialReportController;
use App\Http\Controllers\Scheduling\GameController;
use App\Http\Controllers\Teams\TeamController;
use App\Http\Controllers\Teams\TeamRosterController;
use App\Http\Controllers\Tournaments\TournamentController;
use App\Http\Controllers\Venues\VenueController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/locale', [LocaleController::class, 'update'])->name('locale.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ─── Identity & Access ────────────────────────────────────────────────────

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');

    // User role & permission assignment
    Route::post('/users/{user}/roles', [UserController::class, 'assignRole'])->name('users.roles.assign');
    Route::delete('/users/{user}/roles/{role}', [UserController::class, 'revokeRole'])->name('users.roles.revoke');
    Route::post('/users/{user}/permissions', [UserController::class, 'assignPermission'])->name('users.permissions.assign');

    // Roles
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::post('/roles/{id}/restore', [RoleController::class, 'restore'])->name('roles.restore');

    // Permissions
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    Route::post('/permissions/{id}/restore', [PermissionController::class, 'restore'])->name('permissions.restore');

    // ─── Navigation ───────────────────────────────────────────────────────────

    Route::get('/navigation', [NavigationItemController::class, 'index'])->name('navigation.index');
    Route::get('/navigation/create', [NavigationItemController::class, 'create'])->name('navigation.create');
    Route::post('/navigation', [NavigationItemController::class, 'store'])->name('navigation.store');
    Route::get('/navigation/{navigation}/edit', [NavigationItemController::class, 'edit'])->name('navigation.edit');
    Route::put('/navigation/{navigation}', [NavigationItemController::class, 'update'])->name('navigation.update');
    Route::delete('/navigation/{navigation}', [NavigationItemController::class, 'destroy'])->name('navigation.destroy');
    Route::post('/navigation/{id}/restore', [NavigationItemController::class, 'restore'])->name('navigation.restore');

    // ─── Venues ───────────────────────────────────────────────────────────────

    Route::get('/venues', [VenueController::class, 'index'])->name('venues.index');
    Route::get('/venues/create', [VenueController::class, 'create'])->name('venues.create');
    Route::post('/venues', [VenueController::class, 'store'])->name('venues.store');
    Route::get('/venues/{venue}/edit', [VenueController::class, 'edit'])->name('venues.edit');
    Route::put('/venues/{venue}', [VenueController::class, 'update'])->name('venues.update');
    Route::delete('/venues/{venue}', [VenueController::class, 'destroy'])->name('venues.destroy');
    Route::post('/venues/{id}/restore', [VenueController::class, 'restore'])->name('venues.restore');

    // ─── Tournaments ──────────────────────────────────────────────────────────

    Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments.index');
    Route::get('/tournaments/create', [TournamentController::class, 'create'])->name('tournaments.create');
    Route::post('/tournaments', [TournamentController::class, 'store'])->name('tournaments.store');
    Route::get('/tournaments/{tournament}/edit', [TournamentController::class, 'edit'])->name('tournaments.edit');
    Route::put('/tournaments/{tournament}', [TournamentController::class, 'update'])->name('tournaments.update');
    Route::delete('/tournaments/{tournament}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');
    Route::post('/tournaments/{id}/restore', [TournamentController::class, 'restore'])->name('tournaments.restore');

    // ─── Divisions ────────────────────────────────────────────────────────────

    Route::get('/divisions', [DivisionController::class, 'index'])->name('divisions.index');
    Route::get('/divisions/create', [DivisionController::class, 'create'])->name('divisions.create');
    Route::post('/divisions', [DivisionController::class, 'store'])->name('divisions.store');
    Route::get('/divisions/{division}/edit', [DivisionController::class, 'edit'])->name('divisions.edit');
    Route::put('/divisions/{division}', [DivisionController::class, 'update'])->name('divisions.update');
    Route::delete('/divisions/{division}', [DivisionController::class, 'destroy'])->name('divisions.destroy');
    Route::post('/divisions/{id}/restore', [DivisionController::class, 'restore'])->name('divisions.restore');

    // ─── Competitions ─────────────────────────────────────────────────────────

    Route::get('/competitions', [CompetitionController::class, 'index'])->name('competitions.index');
    Route::get('/competitions/create', [CompetitionController::class, 'create'])->name('competitions.create');
    Route::post('/competitions', [CompetitionController::class, 'store'])->name('competitions.store');
    Route::delete('/competitions/{competition}', [CompetitionController::class, 'destroy'])->name('competitions.destroy');
    Route::post('/competitions/{id}/restore', [CompetitionController::class, 'restore'])->name('competitions.restore');

    // ─── Prices ───────────────────────────────────────────────────────────────

    Route::get('/prices', [PriceController::class, 'index'])->name('prices.index');
    Route::get('/prices/create', [PriceController::class, 'create'])->name('prices.create');
    Route::post('/prices', [PriceController::class, 'store'])->name('prices.store');
    Route::get('/prices/{price}/edit', [PriceController::class, 'edit'])->name('prices.edit');
    Route::put('/prices/{price}', [PriceController::class, 'update'])->name('prices.update');
    Route::delete('/prices/{price}', [PriceController::class, 'destroy'])->name('prices.destroy');
    Route::post('/prices/{id}/restore', [PriceController::class, 'restore'])->name('prices.restore');

    // ─── Categories ───────────────────────────────────────────────────────────

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::post('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');

    // ─── Employees ────────────────────────────────────────────────────────────

    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    Route::post('/employees/{id}/restore', [EmployeeController::class, 'restore'])->name('employees.restore');

    // ─── Players ──────────────────────────────────────────────────────────────

    Route::get('/players', [PlayerController::class, 'index'])->name('players.index');
    Route::get('/players/create', [PlayerController::class, 'create'])->name('players.create');
    Route::post('/players', [PlayerController::class, 'store'])->name('players.store');
    Route::get('/players/{player}/edit', [PlayerController::class, 'edit'])->name('players.edit');
    Route::put('/players/{player}', [PlayerController::class, 'update'])->name('players.update');
    Route::delete('/players/{player}', [PlayerController::class, 'destroy'])->name('players.destroy');
    Route::post('/players/{id}/restore', [PlayerController::class, 'restore'])->name('players.restore');

    // ─── Teams ────────────────────────────────────────────────────────────────

    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::get('/teams/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit');
    Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
    Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
    Route::post('/teams/{id}/restore', [TeamController::class, 'restore'])->name('teams.restore');

    // Competition team rosters
    Route::post('/competition-teams/{competitionTeam}/roster', [TeamRosterController::class, 'store'])
        ->name('competition-teams.roster.store');
    Route::delete('/competition-teams/{competitionTeam}/roster/{roster}', [TeamRosterController::class, 'destroy'])
        ->name('competition-teams.roster.destroy');

    // ─── Player Sanctions ─────────────────────────────────────────────────────

    Route::get('/player-sanctions', [PlayerSanctionController::class, 'index'])->name('player-sanctions.index');
    Route::get('/player-sanctions/create', [PlayerSanctionController::class, 'create'])->name('player-sanctions.create');
    Route::post('/player-sanctions', [PlayerSanctionController::class, 'store'])->name('player-sanctions.store');
    Route::get('/player-sanctions/{playerSanction}/edit', [PlayerSanctionController::class, 'edit'])->name('player-sanctions.edit');
    Route::put('/player-sanctions/{playerSanction}', [PlayerSanctionController::class, 'update'])->name('player-sanctions.update');
    Route::delete('/player-sanctions/{playerSanction}', [PlayerSanctionController::class, 'destroy'])->name('player-sanctions.destroy');
    Route::post('/player-sanctions/{id}/restore', [PlayerSanctionController::class, 'restore'])->name('player-sanctions.restore');

    // ─── Scheduling (Games) ───────────────────────────────────────────────────

    Route::get('/games', [GameController::class, 'index'])->name('games.index');
    Route::get('/games/create', [GameController::class, 'create'])->name('games.create');
    Route::post('/games', [GameController::class, 'store'])->name('games.store');
    Route::get('/games/{game}/edit', [GameController::class, 'edit'])->name('games.edit');
    Route::put('/games/{game}', [GameController::class, 'update'])->name('games.update');
    Route::delete('/games/{game}', [GameController::class, 'destroy'])->name('games.destroy');
    Route::post('/games/{id}/restore', [GameController::class, 'restore'])->name('games.restore');
    Route::post('/games/{game}/cancel', [GameController::class, 'cancel'])->name('games.cancel');

    // Game role assignments (per game)
    Route::post('/games/{game}/roles', [GameRoleAssignmentController::class, 'store'])->name('games.roles.assign');
    Route::delete('/games/{game}/roles/{assignment}', [GameRoleAssignmentController::class, 'destroy'])->name('games.roles.revoke');

    // ─── Match Roles (Game Roles) ─────────────────────────────────────────────

    Route::get('/game-roles', [GameRoleController::class, 'index'])->name('game-roles.index');
    Route::get('/game-roles/create', [GameRoleController::class, 'create'])->name('game-roles.create');
    Route::post('/game-roles', [GameRoleController::class, 'store'])->name('game-roles.store');
    Route::get('/game-roles/{gameRole}/edit', [GameRoleController::class, 'edit'])->name('game-roles.edit');
    Route::put('/game-roles/{gameRole}', [GameRoleController::class, 'update'])->name('game-roles.update');
    Route::delete('/game-roles/{gameRole}', [GameRoleController::class, 'destroy'])->name('game-roles.destroy');
    Route::post('/game-roles/{id}/restore', [GameRoleController::class, 'restore'])->name('game-roles.restore');

    // ─── Finance (Payments) ───────────────────────────────────────────────────

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::post('/payments/{id}/restore', [PaymentController::class, 'restore'])->name('payments.restore');
    Route::post('/payments/{payment}/cancel', [PaymentController::class, 'cancel'])->name('payments.cancel');

    // ─── Payroll ──────────────────────────────────────────────────────────────

    Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index');
    Route::get('/payrolls/create', [PayrollController::class, 'create'])->name('payrolls.create');
    Route::post('/payrolls', [PayrollController::class, 'store'])->name('payrolls.store');
    Route::get('/payrolls/{payroll}/edit', [PayrollController::class, 'edit'])->name('payrolls.edit');
    Route::put('/payrolls/{payroll}', [PayrollController::class, 'update'])->name('payrolls.update');
    Route::delete('/payrolls/{payroll}', [PayrollController::class, 'destroy'])->name('payrolls.destroy');
    Route::post('/payrolls/{id}/restore', [PayrollController::class, 'restore'])->name('payrolls.restore');
    Route::post('/payrolls/{payroll}/cancel', [PayrollController::class, 'cancel'])->name('payrolls.cancel');

    // ─── Media ────────────────────────────────────────────────────────────────

    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::get('/media/create', [MediaController::class, 'create'])->name('media.create');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::get('/media/{media}/edit', [MediaController::class, 'edit'])->name('media.edit');
    Route::put('/media/{media}', [MediaController::class, 'update'])->name('media.update');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    Route::post('/media/{id}/restore', [MediaController::class, 'restore'])->name('media.restore');

    // ─── Reports ──────────────────────────────────────────────────────────────

    Route::get('/reports/financial', [FinancialReportController::class, 'index'])->name('reports.financial.index');
    Route::get('/reports/financial/export', [FinancialReportController::class, 'export'])->name('reports.financial.export');

    // ─── Audit ───────────────────────────────────────────────────────────────

    Route::get('/audit', [AuditController::class, 'index'])
        ->middleware('can:audit.view')
        ->name('audit.index');
    Route::get('/audit/export', [AuditController::class, 'export'])
        ->middleware('can:audit.export')
        ->name('audit.export');
    Route::get('/audit/{id}', [AuditController::class, 'show'])
        ->middleware('can:audit.view')
        ->name('audit.show');
});

require __DIR__.'/auth.php';
