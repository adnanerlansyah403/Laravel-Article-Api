<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Article::query()->insert([
            [
                "title" => fake()->title,
                "description" => fake()->text(),
                "thumbnail" => "https://i.pinimg.com/564x/fb/59/0c/fb590c826010ef7720e986dfaa952b5f.jpg",
                "image" => "https://i.pinimg.com/564x/fb/59/0c/fb590c826010ef7720e986dfaa952b5f.jpg",
                "is_featured" => true,
                "user_id" => 2,
            ],
            [
                "title" => fake()->title,
                "description" => fake()->text(),
                "thumbnail" => "https://i.pinimg.com/236x/2d/cc/b2/2dccb28aa84d00e6e3e4ab97ab69e25c.jpg",
                "image" => "https://i.pinimg.com/236x/2d/cc/b2/2dccb28aa84d00e6e3e4ab97ab69e25c.jpg",
                "user_id" => 2,
                "is_featured" => false,
            ],
            [
                "title" => fake()->title,
                "description" => fake()->text(),
                "thumbnail" => "https://i.pinimg.com/236x/35/47/af/3547af9610db7fc69c90e66524692abd.jpg",
                "image" => "https://i.pinimg.com/236x/35/47/af/3547af9610db7fc69c90e66524692abd.jpg",
                "user_id" => 3,
                "is_featured" => false,
            ],
            [
                "title" => fake()->title,
                "description" => fake()->text(),
                "thumbnail" => "https://i.pinimg.com/236x/81/45/55/8145553ae53c1697b332a4d68014577c.jpg",
                "image" => "https://i.pinimg.com/236x/81/45/55/8145553ae53c1697b332a4d68014577c.jpg",
                "user_id" => 3,
                "is_featured" => false,
            ],
            [
                "title" => fake()->title,
                "description" => fake()->text(),
                "thumbnail" => "https://i.pinimg.com/236x/53/ef/d7/53efd7b8a2a88c1a6c389c40f6663d4b.jpg",
                "image" => "https://i.pinimg.com/236x/53/ef/d7/53efd7b8a2a88c1a6c389c40f6663d4b.jpg",
                "user_id" => 4,
                "is_featured" => false,
            ],
        ]);
    }
}
