<?php

namespace Database\Factories;

use App\Models\Redirect;
use Illuminate\Database\Eloquent\Factories\Factory;

class RedirectFactory extends Factory
{
    protected $model = Redirect::class;

    public function definition()
    {
        return [
            'url_destino' => $this->faker->url,
            'status' => $this->faker->boolean,
            'code' => $this->faker->unique()->randomNumber(8),
        ];
    }
}
