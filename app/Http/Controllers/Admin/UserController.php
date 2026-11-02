<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    // ─── Index ───────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = User::withCount([
            'loans as active_loans_count' => fn($q) => $q->whereIn('status', ['pending', 'borrowed', 'overdue']),
        ]);

        // Filter by search query
        if ($q = $request->input('q')) {
            $query->where(function ($sq) use ($q) {
                $sq->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('nis', 'like', "%{$q}%")
                    ->orWhere('kelas', 'like', "%{$q}%")
                    ->orWhere('member_id', 'like', "%{$q}%");
            });
        }

        // Filter by role
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        // Filter by activation status (activated / pending)
        if ($active = $request->input('active')) {
            if ($active === 'activated') {
                // Users with password set (activated)
                $query->whereNotNull('password');
            } elseif ($active === 'pending') {
                // Users without password (needs activation)
                $query->whereNull('password');
            }
        }

        $users = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // ─── Show ─────────────────────────────────────────────────────────────────

    public function show(User $user)
    {
        $loans = $user->loans()
            ->with('book')
            ->latest()
            ->paginate(10);

        $totalFine    = $user->loans()->sum('fine_amount');
        $unpaidFine   = $user->unpaidFines();
        $activeLoanCount = $user->activeLoans()->count();

        return view('admin.users.show', compact('user', 'loans', 'totalFine', 'unpaidFine', 'activeLoanCount'));
    }

    // ─── Create ───────────────────────────────────────────────────────────────

    public function create()
    {
        return view('admin.users.create');
    }

    // ─── Download Excel Template ───────────────────────────────────────────────

    public function downloadTemplate(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Siswa');

        // Headers
        $headers = ['NIS', 'Nama Siswa', 'Kelas'];
        foreach ($headers as $colIndex => $header) {
            $colLetter = chr(65 + $colIndex);
            $sheet->setCellValue("{$colLetter}1", $header);
        }

        // Style header row
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EA580C'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A1:C1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(26);

        // Contoh baris
        $examples = [
            ['10003', 'Budi Santoso', 'X RPL 1'],
            ['10004', 'Siti Aminah', 'XI TKJ 2'],
            ['10005', 'Ahmad Fauzi', 'XII MIPA 1'],
        ];

        foreach ($examples as $rowIndex => $data) {
            $r = $rowIndex + 2;
            $sheet->setCellValueExplicit("A{$r}", (string) $data[0], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue("B{$r}", $data[1]);
            $sheet->setCellValue("C{$r}", $data[2]);
        }

        foreach (['A', 'B', 'C'] as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 'template_impor_siswa.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    // ─── Import from Excel / CSV ───────────────────────────────────────────────

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv,txt', 'max:5120'],
        ], [
            'file.required' => 'Pilih file Excel atau CSV yang akan diimpor.',
            'file.mimes'    => 'Format file harus berupa .xlsx, .xls, atau .csv.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $file = $request->file('file');

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, false);
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }

        if (empty($rows) || count($rows) < 2) {
            return back()->with('error', 'File tidak memiliki baris data siswa atau kosong.');
        }

        // Deteksi index kolom dari baris pertama (header)
        $headerRow = array_map(fn($val) => strtolower(trim((string) $val)), $rows[0]);
        
        $nisCol = 0;
        $nameCol = 1;
        $kelasCol = 2;

        foreach ($headerRow as $idx => $headerName) {
            if (str_contains($headerName, 'nis')) {
                $nisCol = $idx;
            } elseif (str_contains($headerName, 'nama') || str_contains($headerName, 'name')) {
                $nameCol = $idx;
            } elseif (str_contains($headerName, 'kelas') || str_contains($headerName, 'class')) {
                $kelasCol = $idx;
            }
        }

        $imported = 0;
        $skipped = [];

        // Loop dari baris ke-2 (setelah header)
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];

            $nis = isset($row[$nisCol]) ? trim((string) $row[$nisCol]) : '';
            if (str_contains($nis, '.')) {
                $nis = explode('.', $nis)[0];
            }

            $name = isset($row[$nameCol]) ? trim((string) $row[$nameCol]) : '';
            $kelas = isset($row[$kelasCol]) ? trim((string) $row[$kelasCol]) : null;

            if ($nis === '' && $name === '') {
                continue;
            }

            if ($nis === '' || $name === '') {
                $skipped[] = "Baris " . ($i + 1) . " (NIS atau Nama kosong)";
                continue;
            }

            // Cek apakah NIS sudah terdaftar
            if (User::where('nis', $nis)->exists()) {
                $skipped[] = "NIS {$nis} ({$name})";
                continue;
            }

            User::create([
                'name'      => $name,
                'nis'       => $nis,
                'kelas'     => $kelas ?: null,
                'member_id' => $nis,
                'email'     => null,
                'password'  => null,
                'role'      => 'member',
            ]);

            $imported++;
        }

        $message = "Berhasil mengimpor {$imported} data siswa baru.";
        if (! empty($skipped)) {
            $countSkipped = count($skipped);
            $previewSkipped = implode(', ', array_slice($skipped, 0, 5));
            if ($countSkipped > 5) {
                $previewSkipped .= " dan " . ($countSkipped - 5) . " lainnya";
            }
            return redirect()
                ->route('admin.users.index')
                ->with('success', $message)
                ->with('warning', "{$countSkipped} data dilewati karena NIS sudah terdaftar: {$previewSkipped}.");
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', $message);
    }

    // ─── Store ────────────────────────────────────────────────────────────────

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            $validated['password'] = null;
        }

        if (empty($validated['member_id']) && ! empty($validated['nis'])) {
            $validated['member_id'] = $validated['nis'];
        }

        $user = User::create($validated);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', "Pengguna \"{$user->name}\" berhasil dibuat.");
    }

    // ─── Edit ─────────────────────────────────────────────────────────────────

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // ─── Update ───────────────────────────────────────────────────────────────

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', "Data pengguna \"{$user->name}\" berhasil diperbarui.");
    }

    // ─── Destroy ──────────────────────────────────────────────────────────────

    public function destroy(User $user): RedirectResponse
    {
        // Tidak bisa hapus user dengan peminjaman aktif
        if ($user->activeLoans()->exists()) {
            return back()->with('error', "Pengguna \"{$user->name}\" tidak dapat dihapus karena masih memiliki peminjaman aktif.");
        }

        // Tidak bisa hapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Pengguna \"{$name}\" berhasil dihapus.");
    }
}
