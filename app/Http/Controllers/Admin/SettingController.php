<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // Daftar setting yang bisa dikonfigurasi admin beserta aturan validasinya
    private const SETTINGS = [
        'loan_duration_days' => ['required', 'integer', 'min:1', 'max:365'],
        'fine_per_day'       => ['required', 'integer', 'min:0'],
        'max_active_loans'   => ['required', 'integer', 'min:1', 'max:50'],
        'max_unpaid_fine'    => ['required', 'integer', 'min:0'],
    ];

    public function index()
    {
        $settings = Setting::whereIn('key', array_keys(self::SETTINGS))->pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate(self::SETTINGS);

        foreach (self::SETTINGS as $key => $_rules) {
            Setting::set($key, $request->input($key));
        }

        return back()->with('success', 'Pengaturan sistem berhasil disimpan.');
    }
}
