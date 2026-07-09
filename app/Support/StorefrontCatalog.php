<?php

namespace App\Support;

use App\Models\Product;

class StorefrontCatalog
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        return Product::query()
            ->active()
            ->whereHas('category', fn ($query) => $query->active())
            ->with('category:id,name')
            ->latest('id')
            ->get()
            ->map(fn (Product $product): array => $this->productPayload($product))
            ->all();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $productId): ?array
    {
        $product = Product::query()
            ->active()
            ->whereKey($productId)
            ->whereHas('category', fn ($query) => $query->active())
            ->with('category:id,name')
            ->first();

        return $product ? $this->productPayload($product) : null;
    }

    /**
     * @param  array<int|string, int>  $cart
     * @return array{items: array<int, array<string, mixed>>, total: int, count: int}
     */
    public function cartSummary(array $cart): array
    {
        $items = collect($cart)
            ->map(function (int $quantity, int|string $productId): ?array {
                $product = $this->find((int) $productId);

                if (! $product) {
                    return null;
                }

                $quantity = min(max($quantity, 1), (int) $product['stock']);

                if ($quantity < 1) {
                    return null;
                }

                return [
                    ...$product,
                    'quantity' => $quantity,
                    'subtotal' => $quantity * (int) $product['price'],
                ];
            })
            ->filter()
            ->values()
            ->all();

        return [
            'items' => $items,
            'total' => array_sum(array_column($items, 'subtotal')),
            'count' => array_sum(array_column($items, 'quantity')),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function productPayload(Product $product): array
    {
        return [
            'id' => $product->id,
            'sku' => $product->sku,
            'name' => $product->name,
            'category' => $product->category->name,
            'description' => $product->description,
            'price' => $product->price,
            'stock' => $product->stock,
            'shade' => $product->shade,
        ];
    }
}
