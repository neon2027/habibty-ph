<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        collect([
            ['sku' => 'HBT-LIP-101', 'name' => 'Rosy Matte Lip Tint', 'category' => 'Beauty', 'description' => 'Soft matte tint with a Legazpi-ready rosy finish.', 'price' => 24900, 'stock' => 18, 'shade' => '#f472b6'],
            ['sku' => 'HBT-BAG-204', 'name' => 'Pearl Pink Mini Tote', 'category' => 'Bags', 'description' => 'Compact daily tote with pearl straps and satin lining.', 'price' => 69900, 'stock' => 9, 'shade' => '#f9a8d4'],
            ['sku' => 'HBT-MST-315', 'name' => 'Hydra Glow Face Mist', 'category' => 'Skin Care', 'description' => 'Refreshing mist for a light, dewy finish.', 'price' => 32900, 'stock' => 22, 'shade' => '#fbcfe8'],
            ['sku' => 'HBT-SET-428', 'name' => 'Blush Gift Set', 'category' => 'Gift Sets', 'description' => 'A boxed trio of lip tint, pouch, and scented hand cream.', 'price' => 89900, 'stock' => 7, 'shade' => '#fb7185'],
            ['sku' => 'HBT-ACC-512', 'name' => 'Sakura Hair Claw', 'category' => 'Accessories', 'description' => 'Cloud-light claw clip with glossy pink petals.', 'price' => 15900, 'stock' => 30, 'shade' => '#fda4af'],
            ['sku' => 'HBT-CRM-626', 'name' => 'Cotton Candy Hand Cream', 'category' => 'Body Care', 'description' => 'Pocket-size moisturizing cream with a gentle sweet scent.', 'price' => 19900, 'stock' => 25, 'shade' => '#fecdd3'],
        ])->each(function (array $product): void {
            $category = Category::updateOrCreate(
                ['slug' => Str::slug($product['category'])],
                [
                    'name' => $product['category'],
                    'description' => "HabibtyPH {$product['category']} collection.",
                    'is_active' => true,
                ],
            );

            Product::updateOrCreate(
                ['sku' => $product['sku']],
                [
                    'category_id' => $category->id,
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'shade' => $product['shade'],
                    'is_active' => true,
                ],
            );
        });
    }
}
