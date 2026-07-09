<?php

namespace App\Filament\Widgets;

use App\Support\Reports\SalesReport;
use Filament\Widgets\Widget;

class SalesReportWidget extends Widget
{
    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.sales-report-widget';

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $report = app(SalesReport::class);

        return [
            'rows' => $report->summary(),
            'totalCustomers' => $report->totalCustomers(),
        ];
    }
}
