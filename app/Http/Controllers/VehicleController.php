<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VehicleController extends Controller
{
    public function index()
    {
        $this->authorizeManager();
        $vehicles = Vehicle::orderByDesc('created_at')->get();
        return view('vehicles.index', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $this->authorizeManager();
        $validated = $request->validate([
            'model' => 'required|string|max:255',
            'color' => 'nullable|string|max:100',
            'plate_number' => 'required|string|max:50|unique:vehicles,plate_number',
        ]);
        Vehicle::create($validated);
        return redirect()->route('vehicles.index')->with('success', 'Vehicle added.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $this->authorizeManager();
        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'Vehicle removed.');
    }

    private function authorizeManager(): void
    {
        $user = Auth::user();
        abort_unless($user && $user->role === 'manager', 403, 'Forbidden');
    }
}
