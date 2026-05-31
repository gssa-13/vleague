<?php

// database/factories/NavigationItemFactory.php

namespace Database\Factories;

use App\Models\NavigationItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NavigationItem>
 */
class NavigationItemFactory extends Factory
{
    protected $model = NavigationItem::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'label' => $this->faker->words(2, true),
            'label_key' => null,
            'route_name' => $this->faker->slug(2),
            'icon' => 'fas fa-circle',
            'permission_name' => null,
            'parent_id' => null,
            'sort_order' => $this->faker->numberBetween(1, 100),
        ];
    }

    public function withPermission(string $permission): static
    {
        return $this->state(['permission_name' => $permission]);
    }
}
