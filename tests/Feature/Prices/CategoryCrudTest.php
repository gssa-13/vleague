<?php

// tests/Feature/Prices/CategoryCrudTest.php

use App\Models\Category;
use App\Models\User;

it('redirects unauthenticated user away from categories index', function () {
    $this->get(route('categories.index'))
        ->assertRedirect(route('login'));
});

it('returns 403 when user lacks categories.view permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('categories.index'))
        ->assertForbidden();
});

it('lists categories when user has categories.view permission', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('categories.view');

    Category::factory()->count(2)->create();

    $this->actingAs($viewer)
        ->get(route('categories.index'))
        ->assertOk()
        ->assertViewIs('categories.index');
});

it('creates a category when actor has categories.create permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('categories.create');

    $this->actingAs($actor)
        ->post(route('categories.store'), ['name' => 'Fútbol 7'])
        ->assertRedirect(route('categories.index'));

    $this->assertDatabaseHas('categories', ['name' => 'Fútbol 7']);
});

it('soft deletes a category when actor has categories.delete permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('categories.delete');

    $category = Category::factory()->create();

    $this->actingAs($actor)
        ->delete(route('categories.destroy', $category))
        ->assertRedirect(route('categories.index'));

    $this->assertSoftDeleted('categories', ['id' => $category->id]);
});

it('restores a soft-deleted category when actor has categories.restore permission', function () {
    $actor = User::factory()->create();
    $actor->givePermissionTo('categories.restore');

    $category = Category::factory()->create();
    $category->delete();

    $this->actingAs($actor)
        ->post(route('categories.restore', $category->id))
        ->assertRedirect(route('categories.index'));

    $this->assertNotSoftDeleted('categories', ['id' => $category->id]);
});
