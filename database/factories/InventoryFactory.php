<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Inventory;
use Faker\Generator as Faker;

$factory->define(Inventory::class, function (Faker $faker) {
    return [
        'title' => $faker->text(10),
        'category' => $faker->text(10),
        'description' => $faker->text(),
        'price' => $faker->numberBetween(0, 100),
        'units' => $faker->numberBetween(0, 30),
        'status' => 'unavailable'
    ];
});
