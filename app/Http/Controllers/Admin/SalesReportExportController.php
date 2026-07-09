<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Reports\SalesReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SalesReportExportController extends Controller
{
    public function __invoke(Request $request, SalesReport $report, string $format)
    {
        abort_unless(
            $request->user()?->canAccessPanel(Filament::getPanel('admin')),
            403,
        );

        abort_unless(in_array($format, ['excel', 'pdf'], true), 404);

        $rows = $report->summary();
        $generatedAt = now();

        if ($format === 'pdf') {
            return Pdf::loadView('admin.reports.sales', [
                'rows' => $rows,
                'generatedAt' => $generatedAt,
                'totalCustomers' => $report->totalCustomers(),
            ])->download('habibtyph-sales-report.pdf');
        }

        return response()->streamDownload(function () use ($rows, $generatedAt, $report): void {
            $output = fopen('php://output', 'w');

            fputcsv($output, ['HabibtyPH Legazpi Sales Report']);
            fputcsv($output, ['Generated at', $generatedAt->format('Y-m-d H:i:s')]);
            fputcsv($output, ['Total registered customers', $report->totalCustomers()]);
            fputcsv($output, []);
            fputcsv($output, ['Period', 'Date range', 'Customers', 'Orders', 'Sales']);

            foreach ($rows as $row) {
                fputcsv($output, [
                    $row['label'],
                    $row['starts_at']->format('Y-m-d').' to '.$row['ends_at']->format('Y-m-d'),
                    $row['customers'],
                    $row['orders'],
                    number_format($row['sales'] / 100, 2, '.', ''),
                ]);
            }

            fclose($output);
        }, 'habibtyph-sales-report.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.Str::slug('habibtyph sales report').'.csv"',
        ]);
    }
}
