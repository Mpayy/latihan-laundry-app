<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $title = 'Master Data Pelanggan';

        $customers = Customer::all();

        return view('customers.index', compact('customers', 'title' ));
    }

    public function create()
    {
        $title = 'Tambah Pelanggan';

        return view('customers.create', compact('title' ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cutomer_name' => 'required|string|max:255',
            'phone' => 'required|string',
            'address' => 'required|string|max:255',
            'is_member' => 'required|boolean'
        ]);

        Customer::create([
            'cutomer_name' => $request->cutomer_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_member' => $request->is_member,
        ]);
        return redirect()->route('customers.index');
    }

    public function edit($id)
    {
        $title = "Edit Customer";
        $customer = Customer::find($id);
        return view('customers.edit', compact('customer', 'title'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cutomer_name' => 'required|string|max:255',
            'phone' => 'required|string',
            'address' => 'required|string|max:255',
            'is_member' => 'required|boolean'
        ]);
        $customer = Customer::find($id);
        $customer->cutomer_name = $request->cutomer_name;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->is_member = $request->is_member;
        $customer->save();
        return redirect()->route('customers.index');
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);
        $customer->delete();
        return redirect()->route('customers.index');
    }
}
