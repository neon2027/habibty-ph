<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

function productForCheckout(array $overrides = []): Product
{
    $category = Category::create([
        'name' => 'Beauty',
        'slug' => 'beauty',
        'is_active' => true,
    ]);

    return Product::create([
        'category_id' => $category->id,
        'sku' => 'HBT-TEST-001',
        'name' => 'Test Pink Lip Tint',
        'description' => 'A test product.',
        'price' => 24900,
        'stock' => 5,
        'shade' => '#f472b6',
        'is_active' => true,
        ...$overrides,
    ]);
}

test('checkout saves purchase history and decreases stock', function () {
    $user = User::factory()->create();
    $product = productForCheckout();

    $this->actingAs($user)
        ->withSession(['cart' => [$product->id => 2]])
        ->post(route('checkout'))
        ->assertRedirect(route('dashboard'));

    $order = Order::query()->whereBelongsTo($user)->with('items')->first();

    expect($order)->not->toBeNull()
        ->and($order->total)->toBe(49800)
        ->and($order->items)->toHaveCount(1)
        ->and($order->items->first()->product_name)->toBe('Test Pink Lip Tint')
        ->and($product->refresh()->stock)->toBe(3);
});

test('only admins can download sales reports', function () {
    $customer = User::factory()->create();
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($customer)
        ->get(route('admin.reports.sales', ['format' => 'excel']))
        ->assertForbidden();

    $this->actingAs($admin)
        ->get(route('admin.reports.sales', ['format' => 'excel']))
        ->assertDownload('habibty-legazpi-ph-sales-report.csv');

    $this->actingAs($admin)
        ->get(route('admin.reports.sales', ['format' => 'pdf']))
        ->assertDownload('habibty-legazpi-ph-sales-report.pdf');
});
