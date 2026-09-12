<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Perpustakaan SMKN 5 Surakarta</title>
    <style>
        @page {
            margin: 1.5cm 1.5cm 1.8cm 1.5cm;
            size: a4 portrait;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
            color: #1c1917;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* ── KOP SURAT RESMI ── */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .kop-table td {
            vertical-align: middle;
        }
        .kop-text {
            text-align: center;
        }
        .kop-text .instansi-1 {
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 0;
            color: #1c1917;
        }
        .kop-text .instansi-2 {
            font-size: 12pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 1px 0;
            color: #1c1917;
        }
        .kop-text .sekolah {
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 2px 0;
            color: #ea580c;
        }
        .kop-text .unit {
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin: 1px 0;
            color: #44403c;
        }
        .kop-text .alamat {
            font-size: 8pt;
            color: #57534e;
            margin-top: 3px;
            line-height: 1.3;
        }

        /* Garis KOP Ganda */
        .kop-divider {
            border-top: 2.5px solid #1c1917;
            border-bottom: 0.8px solid #1c1917;
            height: 3px;
            margin-bottom: 16px;
        }

        /* ── JUDUL DOKUMEN ── */
        .doc-title-box {
            text-align: center;
            margin-bottom: 18px;
        }
        .doc-title {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 4px;
            color: #1c1917;
        }
        .doc-meta {
            font-size: 8.5pt;
            color: #57534e;
        }

        /* ── SECTION HEADER ── */
        .section-title {
            font-size: 10.5pt;
            font-weight: bold;
            color: #ea580c;
            border-bottom: 1.5px solid #fed7aa;
            padding-bottom: 3px;
            margin-top: 14px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        /* ── SUMMARY STATS TABLE ── */
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .stats-table td {
            padding: 5px 8px;
            font-size: 9pt;
            border: 1px solid #e7e5e4;
        }
        .stats-label {
            background-color: #f5f5f4;
            color: #44403c;
            font-weight: bold;
            width: 30%;
        }
        .stats-val {
            background-color: #ffffff;
            color: #1c1917;
            font-weight: 600;
            width: 20%;
        }

        /* ── DATA TABLE ── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 8.5pt;
        }
        .data-table th {
            background-color: #f97316;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 6px 7px;
            border: 1px solid #ea580c;
            text-transform: uppercase;
            font-size: 7.5pt;
            letter-spacing: 0.3px;
        }
        .data-table td {
            padding: 5px 7px;
            border: 1px solid #e7e5e4;
            vertical-align: top;
        }
        .data-table tr:nth-child(even) td {
            background-color: #fafaf9;
        }

        /* Badge status */
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-borrowed { background-color: #dbeafe; color: #1e40af; }
        .badge-returned { background-color: #dcfce7; color: #166534; }
        .badge-overdue { background-color: #fee2e2; color: #991b1b; }
        .badge-pending { background-color: #fef3c7; color: #92400e; }

        /* ── SIGNATURE BOX ── */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 24px;
            page-break-inside: avoid;
        }
        .signature-table td {
            vertical-align: top;
            font-size: 9pt;
            width: 50%;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 55px;
            margin-bottom: 2px;
        }
        .signature-nip {
            font-size: 8pt;
            color: #57534e;
        }

        /* ── FOOTER PAGE NUMBER ── */
        .footer {
            position: fixed;
            bottom: -1cm;
            left: 0;
            right: 0;
            font-size: 7.5pt;
            color: #a8a29e;
            text-align: right;
            border-top: 0.5px solid #e7e5e4;
            padding-top: 4px;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT SMKN 5 SURAKARTA --}}
    <table class="kop-table">
        <tr>
            <td class="kop-text">
                <div class="instansi-1">Pemerintah Provinsi Jawa Tengah</div>
                <div class="instansi-2">Dinas Pendidikan dan Kebudayaan</div>
                <div class="sekolah">SMK Negeri 5 Surakarta</div>
                <div class="unit">Unit Perpustakaan & Pusat Sumber Belajar Digital (Libriq.id)</div>
                <div class="alamat">
                    Jl. Adi Sucipto No. 42, Kerten, Kecamatan Laweyan, Kota Surakarta, Jawa Tengah 57143<br>
                    Telepon: 0271 713916 | Pos-el: info@smkn5solo.net | Laman: https://www.smkn5surakarta.sch.id/
                </div>
            </td>
        </tr>
    </table>
    <div class="kop-divider"></div>

    {{-- JUDUL LAPORAN --}}
    <div class="doc-title-box">
        <div class="doc-title">Laporan Eksekutif Sirkulasi & Inventaris Perpustakaan</div>
        <div class="doc-meta">
            Tanggal Cetak: <strong>{{ $reportDate }}</strong>  | Dibuat oleh: {{ $adminName }}
        </div>
    </div>

    {{-- 1. RINGKASAN STATISTIK --}}
    <div class="section-title">I. Rekapitulasi Statistik Sistem</div>
    <table class="stats-table">
        <tr>
            <td class="stats-label">Total Judul Buku</td>
            <td class="stats-val">{{ number_format($totalBooks) }} Judul</td>
            <td class="stats-label">Total Peminjaman (Semua)</td>
            <td class="stats-val">{{ number_format($totalLoansCount) }} Transaksi</td>
        </tr>
        <tr>
            <td class="stats-label">Total Eksemplar (Fisik)</td>
            <td class="stats-val">{{ number_format($totalCopies) }} Eksemplar</td>
            <td class="stats-label">Peminjaman Sedang Berjalan</td>
            <td class="stats-val">{{ number_format($activeLoansCount) }} Buku</td>
        </tr>
        <tr>
            <td class="stats-label">Buku Tersedia di Rak</td>
            <td class="stats-val">{{ number_format($availableCopies) }} Eksemplar</td>
            <td class="stats-label">Keterlambatan (Overdue)</td>
            <td class="stats-val" style="color: #dc2626;">{{ number_format($overdueLoansCount) }} Buku</td>
        </tr>
        <tr>
            <td class="stats-label">Total Anggota Terdaftar</td>
            <td class="stats-val">{{ number_format($totalMembers) }} Siswa/Guru</td>
            <td class="stats-label">Denda Belum Diselesaikan</td>
            <td class="stats-val" style="color: #dc2626;">Rp {{ number_format($unpaidFinesSum, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- 2. DAFTAR KOLEKSI BUKU --}}
    <div class="section-title">II. Daftar Inventaris Koleksi Buku</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px; text-align: center;">No</th>
                <th style="width: 90px;">ISBN</th>
                <th>Judul Buku</th>
                <th style="width: 100px;">Pengarang</th>
                <th style="width: 70px;">Kategori</th>
                <th style="width: 45px; text-align: center;">Rak</th>
                <th style="width: 40px; text-align: center;">Total</th>
                <th style="width: 40px; text-align: center;">Tersedia</th>
            </tr>
        </thead>
        <tbody>
            @forelse($books as $idx => $book)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="font-family: monospace; font-size: 7.5pt;">{{ $book->isbn }}</td>
                    <td><strong>{{ $book->title }}</strong></td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->category }}</td>
                    <td style="text-align: center;">{{ $book->rack_location ?? '-' }}</td>
                    <td style="text-align: center;">{{ $book->total_stock }}</td>
                    <td style="text-align: center; font-weight: bold; color: {{ $book->available_stock > 0 ? '#15803d' : '#dc2626' }};">
                        {{ $book->available_stock }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; color: #78716c; padding: 12px;">Belum ada data koleksi buku.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 3. CATATAN SIRKULASI TERAKHIR --}}
    <div class="section-title">III. Riwayat & Status Sirkulasi Peminjaman Terkini</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25px; text-align: center;">No</th>
                <th style="width: 100px;">Peminjam</th>
                <th>Judul Buku</th>
                <th style="width: 65px;">Tgl Pinjam</th>
                <th style="width: 65px;">Jatuh Tempo</th>
                <th style="width: 60px; text-align: center;">Status</th>
                <th style="width: 65px; text-align: right;">Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $idx => $loan)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td>
                        <strong>{{ $loan->borrower?->name ?? 'N/A' }}</strong><br>
                        <span style="font-size: 7pt; color: #78716c;">{{ $loan->borrower?->email }}</span>
                    </td>
                    <td>{{ $loan->book?->title ?? 'Buku Dihapus' }}</td>
                    <td>{{ $loan->borrowed_date ? \Carbon\Carbon::parse($loan->borrowed_date)->format('d/m/Y') : '-' }}</td>
                    <td>{{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') : '-' }}</td>
                    <td style="text-align: center;">
                        <span class="badge badge-{{ $loan->status }}">
                            {{ strtoupper($loan->status) }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        @if($loan->fine_amount > 0)
                            <span style="color: #dc2626; font-weight: bold;">Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}</span>
                        @else
                            <span style="color: #78716c;">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #78716c; padding: 12px;">Belum ada catatan transaksi sirkulasi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 4. PENGESAHAN / TANDA TANGAN --}}
    <table class="signature-table">
        <tr>
            <td style="padding-left: 20px;">
                Mengetahui,<br>
                <strong>Kepala SMK Negeri 5 Surakarta</strong>
                <div class="signature-name">Bp. Sugiyono, S.Pd., M.Si</div>
                <div class="signature-nip">NIP. 19751211 200501 1 005</div>
            </td>
            <td style="text-align: right; padding-right: 20px;">
                Surakarta, {{ $reportDate }}<br>
                <strong>Pengelola Perpustakaan (Admin)</strong>
                <div class="signature-name">{{ $adminName }}</div>
                <div class="signature-nip">NIP / ID: LIBRIQ-ADM-{{ auth()->id() ?? '01' }}</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Dicetak melalui Sistem Otomasi Perpustakaan Libriq.id &bull; SMK Negeri 5 Surakarta &bull; Halaman Dokumen Resmi
    </div>

</body>
</html>
