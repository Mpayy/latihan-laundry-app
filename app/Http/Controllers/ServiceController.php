<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
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

    public function store(ServiceRequest $request)
    {
        $validateData = $request->validated();
        Service::create($validateData);
        return redirect()->route('services.index');
    }

    public function edit(Service $service)
    {
        $title = "Edit Service";
        return view('services.edit', compact('service', 'title'));
    }

    public function update(ServiceRequest $request, Service $service)
    {
        $validateData = $request->validated();
        $service->update($validateData);
        return redirect()->route('services.index');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index');
    }
}
