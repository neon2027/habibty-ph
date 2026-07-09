<?php

namespace App\Support\Reports;

use App\Models\Order;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class SalesReport
{
    /**
     * @return Collection<int, array{key: string, label: string, starts_at: CarbonImmutable, ends_at: CarbonImmutable, customers: int, orders: int, sales: int}>
     */
    public function summary(): Collection
    {
        return collect($this->periods())
            ->map(function (array $period): array {
                $orders = Order::query()
                    ->whereBetween('placed_at', [$period['starts_at'], $period['ends_at']]);

                return [
                    ...$period,
                    'customers' => (clone $orders)->distinct('user_id')->count('user_id'),
                    'orders' => (clone $orders)->count(),
                    'sales' => (int) (clone $orders)->sum('total'),
                ];
            })
            ->values();
    }

    public function totalCustomers(): int
    {
        return User::query()->where('is_admin', false)->count();
    }

    /**
     * @return array<int, array{key: string, label: string, starts_at: CarbonImmutable, ends_at: CarbonImmutable}>
     */
    private function periods(): array
    {
        $now = CarbonImmutable::now();

        return [
            [
                'key' => 'weekly',
                'label' => 'This week',
                'starts_at' => $now->startOfWeek(),
                'ends_at' => $now->endOfWeek(),
            ],
            [
                'key' => 'monthly',
                'label' => 'This month',
                'starts_at' => $now->startOfMonth(),
                'ends_at' => $now->endOfMonth(),
            ],
            [
                'key' => 'quarterly',
                'label' => 'This quarter',
                'starts_at' => $now->startOfQuarter(),
                'ends_at' => $now->endOfQuarter(),
            ],
            [
                'key' => 'annual',
                'label' => 'This year',
                'starts_at' => $now->startOfYear(),
                'ends_at' => $now->endOfYear(),
            ],
        ];
    }
}
