<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoucherRequest;
use App\Models\Voucher;

class VoucherController extends Controller
{
    public function index()
    {
        $title = 'Master Data Voucher';
        $vouchers = Voucher::latest()->get();
        return view('vouchers.index', compact('vouchers', 'title'));
    }

    public function create()
    {
        $title = 'Tambah Voucher';
        return view('vouchers.create', compact('title'));
    }

    public function store(VoucherRequest $request)
    {
        $validateData = $request->validated();
        Voucher::create($validateData);
        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil ditambahkan.');
    }

    public function edit(Voucher $voucher)
    {
        $title = 'Edit Voucher';
        return view('vouchers.edit', compact('voucher', 'title'));
    }

    public function update(VoucherRequest $request, Voucher $voucher)
    {
        $validateData = $request->validated();
        $voucher->update($validateData);
        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil diperbarui.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('vouchers.index')->with('success', 'Voucher berhasil dihapus.');
    }
}
