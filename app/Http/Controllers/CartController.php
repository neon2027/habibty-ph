<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Support\StorefrontCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CartController extends Controller
{
    public function store(Request $request, StorefrontCatalog $catalog): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer'],
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:10'],
        ]);

        $product = $catalog->find((int) $validated['product_id']);

        if (! $product || (int) $product['stock'] < 1) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('Product was not found or is out of stock.')]);

            return back();
        }

        $cart = $request->session()->get('cart', []);
        $productId = (int) $product['id'];
        $quantity = (int) ($validated['quantity'] ?? 1);
        $cart[$productId] = min(((int) ($cart[$productId] ?? 0)) + $quantity, (int) $product['stock']);

        $request->session()->put('cart', $cart);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Added to cart.')]);

        return back();
    }

    public function destroy(Request $request, int $productId): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$productId]);

        $request->session()->put('cart', $cart);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Removed from cart.')]);

        return back();
    }

    public function checkout(Request $request, StorefrontCatalog $catalog): RedirectResponse
    {
        $cart = $catalog->cartSummary($request->session()->get('cart', []));

        if ($cart['count'] === 0) {
            Inertia::flash('toast', ['type' => 'error', 'message' => __('Your cart is empty.')]);

            return back();
        }

        DB::transaction(function () use ($cart, $request): void {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'order_number' => $this->nextOrderNumber(),
                'status' => Order::STATUS_PAID,
                'subtotal' => $cart['total'],
                'total' => $cart['total'],
                'placed_at' => now(),
            ]);

            foreach ($cart['items'] as $item) {
                $product = Product::query()->lockForUpdate()->find($item['id']);

                if (! $product || ! $product->is_active || $product->stock < $item['quantity']) {
                    abort(422, 'One of the products is no longer available in the requested quantity.');
                }

                $product->decrement('stock', $item['quantity']);

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'sku' => $product->sku,
                    'unit_price' => $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);
            }
        });

        $request->session()->forget('cart');

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Checkout received. Your purchase history has been updated.')]);

        return to_route('dashboard');
    }

    private function nextOrderNumber(): string
    {
        do {
            $orderNumber = 'HBT-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }
}
