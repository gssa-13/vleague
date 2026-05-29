<?php

// database/factories/MediaFactory.php

namespace Database\Factories;

use App\Models\Media;
use App\Models\MediaType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    protected $model = Media::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'media_type_id' => MediaType::factory(),
            'name' => $this->faker->words(2, true),
            'file_path' => 'media/'.$this->faker->uuid().'.jpg',
        ];
    }
}
