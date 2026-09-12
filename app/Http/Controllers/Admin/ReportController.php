<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Ensure Dompdf classes are loaded even before composer dump-autoload finishes.
     */
    private function registerDompdfAutoloader(): void
    {
        if (!class_exists(\Dompdf\Dompdf::class, false)) {
            spl_autoload_register(function ($class) {
                $vendorDir = base_path('vendor');
                $prefixes = [
                    'Barryvdh\\DomPDF\\' => $vendorDir . '/barryvdh/laravel-dompdf/src/',
                    'Dompdf\\'           => $vendorDir . '/dompdf/dompdf/src/',
                    'FontLib\\'          => $vendorDir . '/dompdf/php-font-lib/src/FontLib/',
                    'Svg\\'              => $vendorDir . '/dompdf/php-svg-lib/src/Svg/',
                    'Masterminds\\'      => $vendorDir . '/masterminds/html5/src/',
                    'Sabberworm\\CSS\\'  => $vendorDir . '/sabberworm/php-css-parser/src/',
                ];

                if ($class === 'Dompdf\\Cpdf') {
                    $cpdfFile = $vendorDir . '/dompdf/dompdf/lib/Cpdf.php';
                    if (file_exists($cpdfFile)) {
                        require_once $cpdfFile;
                        return true;
                    }
                }

                foreach ($prefixes as $prefix => $baseDir) {
                    $len = strlen($prefix);
                    if (strncmp($prefix, $class, $len) === 0) {
                        $relativeClass = substr($class, $len);
                        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
                        if (file_exists($file)) {
                            require_once $file;
                            return true;
                        }
                    }
                }
                return false;
            }, true, true);
        }
    }

    /**
     * Export system report as PDF formatted for SMKN 5 Surakarta.
     */
    public function exportPdf(Request $request)
    {
        $this->registerDompdfAutoloader();

        // 1. Statistics
        $totalBooks          = Book::count();
        $totalCopies         = (int) Book::sum('total_stock');
        $availableCopies     = (int) Book::sum('available_stock');
        $borrowedCopies      = $totalCopies - $availableCopies;
        $totalMembers        = User::where('role', 'member')->count();
        $totalLoansCount     = Loan::count();
        $activeLoansCount    = Loan::whereIn('status', ['borrowed', 'overdue'])->count();
        $overdueLoansCount   = Loan::where('status', 'overdue')->count();
        $returnedLoansCount  = Loan::where('status', 'returned')->count();
        $totalFinesSum       = (int) Loan::sum('fine_amount');
        $unpaidFinesSum      = (int) Loan::whereNull('fine_paid_at')->where('fine_amount', '>', 0)->sum('fine_amount');
        $paidFinesSum        = (int) Loan::whereNotNull('fine_paid_at')->sum('fine_amount');

        // 2. Data Lists
        $books = Book::orderBy('title')->get();
        $loans = Loan::with(['borrower', 'book'])
            ->latest()
            ->limit(50)
            ->get();

        $generatedAt = Carbon::now()->translatedFormat('d F Y H:i');
        $reportDate  = Carbon::now()->translatedFormat('d F Y');
        $adminName   = auth()->user()?->name ?? 'Administrator Perpustakaan';

        $data = compact(
            'totalBooks',
            'totalCopies',
            'availableCopies',
            'borrowedCopies',
            'totalMembers',
            'totalLoansCount',
            'activeLoansCount',
            'overdueLoansCount',
            'returnedLoansCount',
            'totalFinesSum',
            'unpaidFinesSum',
            'paidFinesSum',
            'books',
            'loans',
            'generatedAt',
            'reportDate',
            'adminName'
        );

        // Render HTML view
        $html = view('admin.reports.pdf', $data)->render();

        // Initialize and generate PDF with Dompdf
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('a4', 'portrait');
        $dompdf->render();

        $fileName = 'Laporan-Perpustakaan-SMKN5-Surakarta-' . Carbon::now()->format('Ymd-His') . '.pdf';

        return response($dompdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}
