<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request (mendukung NIS atau Email + Password).
     */
    public function login(Request $request)
    {
        $loginInput = trim((string) $request->input('login', $request->input('email', '')));
        $password   = (string) $request->input('password', '');

        $request->validate([
            'login'    => ['required_without:email'],
            'email'    => ['required_without:login'],
            'password' => ['required'],
        ], [
            'login.required_without' => 'NIS atau Email wajib diisi.',
            'email.required_without' => 'NIS atau Email wajib diisi.',
            'password.required'      => 'Password wajib diisi.',
        ]);

        // Tentukan apakah input berupa Email atau NIS
        $isEmail = filter_var($loginInput, FILTER_VALIDATE_EMAIL);
        $field   = $isEmail ? 'email' : 'nis';

        // Cek apakah akun ada tapi belum pernah diaktivasi (password masih kosong)
        $potentialUser = User::where($field, $loginInput)->first();
        if ($potentialUser && $potentialUser->needsActivation()) {
            return back()->withErrors([
                'login' => 'Akun dengan ' . strtoupper($field) . " '{$loginInput}' belum diaktivasi (belum memiliki password). Silakan klik tombol 'Aktivasi Akun Siswa Baru' di bawah terlebih dahulu.",
            ])->withInput(['login' => $loginInput, 'email' => $loginInput]);
        }

        $credentials = [$field => $loginInput, 'password' => $password];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Pilihan Rute Tujuan Berdasarkan Role
            $targetRoute = $user->role === 'admin' 
                ? route('admin.dashboard') 
                : route('member.dashboard');

            return redirect()->intended($targetRoute);
        }

        return back()->withErrors([
            'login' => 'NIS/Email atau password yang Anda masukkan salah.',
        ])->withInput(['login' => $loginInput, 'email' => $loginInput]);
    }

    /**
     * Tampilkan formulir aktivasi akun siswa baru (pertama kali masuk).
     */
    public function showActivateForm(Request $request)
    {
        $activatingUserId = session('activating_user_id');
        $student = $activatingUserId ? User::find($activatingUserId) : null;

        // Jika user sudah memiliki password, bersihkan session aktivasi
        if ($student && $student->isActivated()) {
            session()->forget('activating_user_id');
            $student = null;
        }

        return view('auth.activate', compact('student'));
    }

    /**
     * Verifikasi tahap 1: Cek kecocokan NIS dan Nama Siswa yang diimpor Admin.
     */
    public function verifyForActivation(Request $request)
    {
        $validated = $request->validate([
            'nis'  => ['required', 'string'],
            'name' => ['required', 'string'],
        ], [
            'nis.required'  => 'NIS (Nomor Induk Siswa) wajib diisi.',
            'name.required' => 'Nama lengkap siswa wajib diisi.',
        ]);

        $cleanNis  = trim($validated['nis']);
        $cleanName = trim($validated['name']);

        $user = User::where('nis', $cleanNis)->first();

        if (! $user) {
            return back()->withErrors([
                'nis' => "NIS '{$cleanNis}' tidak terdaftar di sistem perpustakaan. Pastikan pihak admin sekolah telah mendaftarkan data Anda.",
            ])->withInput();
        }

        if ($user->isActivated()) {
            return back()->withErrors([
                'nis' => "Akun dengan NIS '{$cleanNis}' sudah aktif sebelumnya. Silakan login langsung menggunakan NIS/Email dan password Anda.",
            ])->withInput();
        }

        // Cek kecocokan nama (case-insensitive)
        if (strcasecmp(trim($user->name), $cleanName) !== 0) {
            return back()->withErrors([
                'name' => "Nama yang Anda masukkan tidak cocok dengan data terdaftar untuk NIS {$cleanNis}. Pastikan penulisan nama sama persis dengan yang didaftarkan pihak sekolah.",
            ])->withInput();
        }

        // Simpan ID user ke session untuk tahap 2 aktivasi
        session(['activating_user_id' => $user->id]);

        return redirect()->route('activate')->with('status', 'Data siswa berhasil diverifikasi! Silakan lengkapi email dan buat password baru Anda.');
    }

    /**
     * Selesaikan tahap 2: Simpan Email dan Password baru untuk siswa.
     */
    public function completeActivation(Request $request)
    {
        $activatingUserId = session('activating_user_id');
        if (! $activatingUserId) {
            return redirect()->route('activate')->withErrors([
                'error' => 'Sesi aktivasi telah berakhir atau tidak valid. Silakan verifikasi NIS dan Nama Anda kembali.',
            ]);
        }

        $user = User::find($activatingUserId);
        if (! $user || $user->isActivated()) {
            session()->forget('activating_user_id');
            return redirect()->route('login')->with('info', 'Akun Anda sudah aktif. Silakan langsung login.');
        }

        $validated = $request->validate([
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'email.required'     => 'Email wajib diisi.',
            'email.email'        => 'Format email tidak valid.',
            'email.unique'       => 'Email ini sudah terdaftar oleh pengguna lain.',
            'password.required'  => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
        ]);

        $user->update([
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        session()->forget('activating_user_id');

        // Otomatis login siswa dan arahkan ke dashboard
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('member.dashboard')->with('success', "Selamat datang, {$user->name}! Akun perpustakaan Anda berhasil diaktivasi.");
    }

    /**
     * Batalkan sesi aktivasi (kembali ke input NIS & Nama).
     */
    public function cancelActivation()
    {
        session()->forget('activating_user_id');
        return redirect()->route('activate');
    }

    /**
     * Halaman registrasi publik dinonaktifkan (diarahkan ke login).
     */
    public function showRegisterForm()
    {
        return redirect()->route('login')->with('info', 'Pendaftaran akun mandiri dinonaktifkan. Akun siswa didaftarkan langsung oleh administrator sekolah. Jika NIS Anda sudah didaftarkan, silakan klik tombol Aktivasi Akun.');
    }

    /**
     * Handle registration request (dinonaktifkan).
     */
    public function register(Request $request)
    {
        return redirect()->route('login')->with('info', 'Pendaftaran akun mandiri dinonaktifkan.');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
