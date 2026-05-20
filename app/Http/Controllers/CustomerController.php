<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $title = 'Master Data Pelanggan';
        $customers = Customer::where("is_member","=", 1)->get();
        $customersNonMember = Customer::where("is_member","=", 0)->get();

        return view('customers.index', compact('customers', 'title', "customersNonMember" ));
    }

    public function create()
    {
        $title = 'Tambah Pelanggan';
        return view('customers.create', compact('title' ));
    }

    public function store(CustomerRequest $request)
    {
        $validateData = $request->validated();
        Customer::create($validateData);
        return redirect()->route('customers.index')->with('success', 'Data berhasil ditambah!');
    }

    public function edit(Customer $customer)
    {
        $title = "Edit Customer";
        return view('customers.edit', compact('customer', 'title'));
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        $ValidateData = $request->validated();
        $customer->update($ValidateData);
        return redirect()->route('customers.index')->with('success', 'Data berhasil ditambah!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index');
    }
}
