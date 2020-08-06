<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Admin\ProductCategory;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

$factory->define(ProductCategory::class, function (Faker $faker) {
    $path = base_path('public/uploads/images/product-categories'); // for windows
//    $path = public_path('public/uploads/images/product-categories'); // for linux
    if (!File::isDirectory($path)) {
        File::makeDirectory($path, 0777, true);
    }

    return [
        'name' => $name = $faker->name,
        'slug' => Str::slug($name),
        'description' => $faker->paragraph(rand(100, 150)),
        'thumbnail' => $faker->image('public/uploads/images/product-categories', 300, 300, 'food', false),
    ];
});
