<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $title = 'Data Voucher';
        $vouchers = Voucher::latest()->get();
        return view('vouchers.index', compact('vouchers', 'title'));
    }

    public function create()
    {
        $title = 'Tambah Voucher';
        return view('vouchers.create', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|unique:vouchers,voucher_code',
            'discount_precentage' => 'required|numeric|min:1|max:100',
            'expired_at' => 'required|date',
        ]);

        Voucher::create([
            'voucher_code' => $request->voucher_code,
            'discount_precentage' => $request->discount_precentage,
            'expired_at' => $request->expired_at,
            'is_active' => 1,
        ]);

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $title = 'Edit Voucher';
        $voucher = Voucher::findOrFail($id);
        return view('vouchers.edit', compact('voucher', 'title'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'voucher_code' => 'required|unique:vouchers,voucher_code,' . $id,
            'discount_precentage' => 'required|numeric|min:1|max:100',
            'expired_at' => 'required|date',
            'is_active' => 'required|boolean',
        ]);

        $voucher = Voucher::findOrFail($id);
        $voucher->update($request->all());

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil dihapus.');
    }
}
