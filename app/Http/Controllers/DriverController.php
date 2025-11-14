<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::orderBy('id', 'desc')->get();

        return view('drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('drivers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'contact_number' => ['nullable', 'string', 'max:100'],
            'license' => ['nullable', 'string', 'max:255'],
        ]);

        $full = trim(sprintf('%s %s %s', $validated['first_name'], $validated['middle_name'] ?? '', $validated['last_name']));

        Driver::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'contact_info' => $validated['contact_number'] ?? null,
            'license' => $validated['license'] ?? null,
            'name' => $full,
            'contact_number' => $validated['contact_number'] ?? null,
        ]);

        return redirect()->route('drivers.index')->with('success', 'Driver added');
    }

    public function edit(Driver $driver)
    {
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'contact_number' => ['nullable', 'string', 'max:100'],
            'license' => ['nullable', 'string', 'max:255'],
        ]);

        $full = trim(sprintf('%s %s %s', $validated['first_name'], $validated['middle_name'] ?? '', $validated['last_name']));

        $driver->update([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'contact_info' => $validated['contact_number'] ?? null,
            'license' => $validated['license'] ?? null,
            'name' => $full,
            'contact_number' => $validated['contact_number'] ?? null,
        ]);

        return redirect()->route('drivers.index')->with('success', 'Driver updated');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();

        return redirect()->route('drivers.index')->with('success', 'Driver removed');
    }
}
