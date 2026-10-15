<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class DummyMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $restaurantId = 1; // From the user with mobile 9121042036
        
        $items = [
            // Starters
            ['item_name' => 'Chicken Tikka', 'price' => 240, 'category' => 'Starters', 'timing' => 'All Day', 'image_url' => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?auto=format&fit=crop&w=400&q=80'],
            ['item_name' => 'Paneer 65', 'price' => 190, 'category' => 'Starters', 'timing' => 'All Day', 'image_url' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?auto=format&fit=crop&w=400&q=80'],
            ['item_name' => 'Chilli Chicken', 'price' => 220, 'category' => 'Starters', 'timing' => 'All Day', 'image_url' => 'https://images.unsplash.com/photo-1626645738196-c2a7c87a8f58?auto=format&fit=crop&w=400&q=80'],
            ['item_name' => 'Veg Manchurian', 'price' => 170, 'category' => 'Starters', 'timing' => 'All Day', 'image_url' => 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?auto=format&fit=crop&w=400&q=80'],
            ['item_name' => 'Mutton Seekh Kebab', 'price' => 320, 'category' => 'Starters', 'timing' => 'Dinner', 'image_url' => 'https://images.unsplash.com/photo-1603360946369-dc9bb6258143?auto=format&fit=crop&w=400&q=80'],
            
            // Biryani
            ['item_name' => 'Hyderabadi Chicken Dum Biryani', 'price' => 350, 'category' => 'Biryani', 'timing' => 'Lunch/Dinner', 'image_url' => 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?auto=format&fit=crop&w=400&q=80'],
            ['item_name' => 'Mutton Biryani', 'price' => 420, 'category' => 'Biryani', 'timing' => 'Lunch/Dinner', 'image_url' => 'https://images.unsplash.com/photo-1631515243349-e0cb75fb8d3a?auto=format&fit=crop&w=400&q=80'],
            ['item_name' => 'Paneer Biryani', 'price' => 280, 'category' => 'Biryani', 'timing' => 'Lunch/Dinner', 'image_url' => 'https://images.unsplash.com/photo-1589301760014-d929f39ce9de?auto=format&fit=crop&w=400&q=80'],
            ['item_name' => 'Special Prawns Biryani', 'price' => 450, 'category' => 'Biryani', 'timing' => 'Lunch/Dinner', 'image_url' => 'https://images.unsplash.com/photo-1550989460-0adf9ea622e2?auto=format&fit=crop&w=400&q=80'],
            
            // Tiffins
            ['item_name' => 'Masala Dosa', 'price' => 80, 'category' => 'Tiffins', 'timing' => 'Breakfast', 'image_url' => 'https://images.unsplash.com/photo-1589301760014-d929f39ce9de?auto=format&fit=crop&w=400&q=80'],
            ['item_name' => 'Idli Sambar', 'price' => 60, 'category' => 'Tiffins', 'timing' => 'Breakfast', 'image_url' => 'https://images.unsplash.com/photo-1589301760014-d929f39ce9de?auto=format&fit=crop&w=400&q=80'],
            ['item_name' => 'Puri Curry', 'price' => 70, 'category' => 'Tiffins', 'timing' => 'Breakfast', 'image_url' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=400&q=80'],
            ['item_name' => 'Vada', 'price' => 65, 'category' => 'Tiffins', 'timing' => 'Breakfast', 'image_url' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&w=400&q=80'],
            
            // Main Course
            ['item_name' => 'Butter Chicken', 'price' => 310, 'category' => 'Main Course', 'timing' => 'Lunch/Dinner', 'image_url' => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?auto=format&fit=crop&w=400&q=80'],
            ['item_name' => 'Dal Makhani', 'price' => 220, 'category' => 'Main Course', 'timing' => 'Lunch/Dinner', 'image_url' => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?auto=format&fit=crop&w=400&q=80'],
            ['item_name' => 'Kadai Paneer', 'price' => 260, 'category' => 'Main Course', 'timing' => 'Lunch/Dinner', 'image_url' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?auto=format&fit=crop&w=400&q=80'],
            ['item_name' => 'Garlic Naan', 'price' => 50, 'category' => 'Main Course', 'timing' => 'Lunch/Dinner', 'image_url' => 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?auto=format&fit=crop&w=400&q=80'],
            
            // Desserts
            ['item_name' => 'Gulab Jamun', 'price' => 90, 'category' => 'Desserts', 'timing' => 'All Day', 'image_url' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=400&q=80'],
            ['item_name' => 'Rasmalai', 'price' => 120, 'category' => 'Desserts', 'timing' => 'All Day', 'image_url' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=400&q=80'],
            ['item_name' => 'Double ka Meetha', 'price' => 150, 'category' => 'Desserts', 'timing' => 'All Day', 'image_url' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?auto=format&fit=crop&w=400&q=80'],
            ['item_name' => 'Ice Cream Sundae', 'price' => 180, 'category' => 'Desserts', 'timing' => 'All Day', 'image_url' => 'https://images.unsplash.com/photo-1563805042-7684c8a9e9ce?auto=format&fit=crop&w=400&q=80'],
        ];

        foreach ($items as $item) {
            $filename = 'menus/' . uniqid() . '.jpg';
            $imageContent = @file_get_contents($item['image_url']);
            
            if ($imageContent) {
                if (!file_exists(storage_path('app/public/menus'))) {
                    mkdir(storage_path('app/public/menus'), 0755, true);
                }
                file_put_contents(storage_path('app/public/' . $filename), $imageContent);
            } else {
                $filename = null;
            }

            Menu::create([
                'restaurant_id' => $restaurantId,
                'item_name' => $item['item_name'],
                'price' => $item['price'],
                'category' => $item['category'],
                'timing_type' => $item['timing'],
                'availability' => 1,
                'image' => $filename,
            ]);
        }
    }
}
