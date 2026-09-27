<?php

namespace App\Http\Middleware;

use App\Models\Loan;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SyncOverdueLoans
{
    /**
     * Handle an incoming request.
     * Otomatis menyinkronkan status overdue dan denda berjalan saat ada request web.
     */
    public function handle(Request $request, Closure $next): Response
    {
        Loan::syncOverdueLoans();

        return $next($request);
    }
}
