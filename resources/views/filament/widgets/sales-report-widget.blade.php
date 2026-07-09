<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-950 dark:text-white">
                    Sales and customer report
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Weekly, monthly, quarterly, and annual totals from completed checkout orders.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <x-filament::button
                    tag="a"
                    color="gray"
                    href="{{ route('admin.reports.sales', ['format' => 'excel']) }}"
                >
                    Download Excel
                </x-filament::button>

                <x-filament::button
                    tag="a"
                    color="primary"
                    href="{{ route('admin.reports.sales', ['format' => 'pdf']) }}"
                >
                    Download PDF
                </x-filament::button>
            </div>
        </div>

        <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-lg border border-gray-200 p-4 dark:border-white/10">
                <p class="text-sm text-gray-500 dark:text-gray-400">Registered customers</p>
                <p class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">{{ number_format($totalCustomers) }}</p>
            </div>

            @foreach ($rows as $row)
                <div class="rounded-lg border border-gray-200 p-4 dark:border-white/10">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $row['label'] }}</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">PHP {{ number_format($row['sales'] / 100, 2) }}</p>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        {{ number_format($row['customers']) }} customers, {{ number_format($row['orders']) }} orders
                    </p>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
