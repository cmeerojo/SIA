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
            'name' => ['required', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:100'],
        ]);

        $name = trim($validated['name']);
        $parts = preg_split('/\s+/', $name, 2);
        $firstName = $parts[0] ?? '';
        $lastName = $parts[1] ?? '';

        Driver::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'contact_info' => $validated['contact_number'] ?? null,
            // Keep legacy fields too if present in model for backward display
            'name' => $validated['name'],
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
            'name' => ['required', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:100'],
        ]);

        $name = trim($validated['name']);
        $parts = preg_split('/\s+/', $name, 2);
        $firstName = $parts[0] ?? '';
        $lastName = $parts[1] ?? '';

        $driver->update([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'contact_info' => $validated['contact_number'] ?? null,
            'name' => $validated['name'],
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
