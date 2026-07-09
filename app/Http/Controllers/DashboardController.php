<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $orders = Order::query()
            ->whereBelongsTo($request->user())
            ->with('items:id,order_id,product_name,sku,unit_price,quantity,subtotal')
            ->latest('placed_at')
            ->limit(10)
            ->get();

        return Inertia::render('Dashboard', [
            'purchaseSummary' => [
                'orders' => Order::query()->whereBelongsTo($request->user())->count(),
                'items' => OrderItem::query()
                    ->whereHas('order', fn ($query) => $query->whereBelongsTo($request->user()))
                    ->sum('quantity'),
                'spent' => Order::query()->whereBelongsTo($request->user())->sum('total'),
            ],
            'orders' => $orders->map(fn (Order $order): array => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'total' => $order->total,
                'placed_at' => $order->placed_at->format('M d, Y h:i A'),
                'items' => $order->items->map(fn ($item): array => [
                    'id' => $item->id,
                    'product_name' => $item->product_name,
                    'sku' => $item->sku,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                ])->all(),
            ])->all(),
        ]);
    }
}
