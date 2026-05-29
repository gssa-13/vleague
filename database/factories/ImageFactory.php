<?php

// database/factories/ImageFactory.php

namespace Database\Factories;

use App\Models\Image;
use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Image>
 */
class ImageFactory extends Factory
{
    protected $model = Image::class;

    public function definition(): array
    {
        return [
            'legacy_id' => null,
            'media_id' => Media::factory(),
            'url' => 'images/'.$this->faker->uuid().'.jpg',
            'link' => $this->faker->optional()->url(),
        ];
    }
}
