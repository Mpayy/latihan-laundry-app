<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $title = 'Master Data Services';

        $services = Service::all();

        return view('services.index', compact('services', 'title' ));
    }

    public function create()
    {
        $title = 'Tambah Service';

        return view('services.create', compact('title' ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'price' => 'required|integer',
            'description' => 'required|string|max:255'
        ]);

        Service::create([
            'service_name' => $request->service_name,
            'price' => $request->price,
            'description' => $request->description,
        ]);
        return redirect()->route('services.index');
    }

    public function edit($id)
    {
        $title = "Edit Service";
        $service = Service::find($id);
        return view('services.edit', compact('service', 'title'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'price' => 'required|integer',
            'description' => 'required|string|max:255'
        ]);
        $service = Service::find($id);
        $service->service_name = $request->service_name;
        $service->price = $request->price;
        $service->description = $request->description;
        $service->save();
        return redirect()->route('services.index');
    }

    public function destroy($id)
    {
        $customer = Service::find($id);
        $customer->delete();
        return redirect()->route('customers.index');
    }
}
