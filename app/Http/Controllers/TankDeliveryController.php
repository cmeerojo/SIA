<?php

namespace App\Http\Controllers;

use App\Models\Tank;
use App\Models\TankDelivery;
use App\Models\TankMovement;
use App\Models\Customer;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TankDeliveryController extends Controller
{
    public function index()
    {
        $deliveries = TankDelivery::with(['tank', 'customer', 'driver'])->orderBy('created_at', 'desc')->get();
        $tanks = Tank::where('status', 'filled')->get();
        $customers = Customer::all();
        $drivers = Driver::all();

        return view('tank_deliveries.index', compact('deliveries', 'tanks', 'customers', 'drivers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tank_id' => ['required', 'exists:tanks,id'],
            'customer_id' => ['required', 'exists:customers,id'],
            'driver_id' => ['nullable', 'exists:drivers,id'],
            'date_delivered' => ['nullable', 'date'],
        ]);

        // wrap in transaction: create delivery, update tank status, and record movement
        DB::transaction(function () use ($validated) {
            $delivery = TankDelivery::create([
                'tank_id' => $validated['tank_id'],
                'customer_id' => $validated['customer_id'],
                'driver_id' => $validated['driver_id'] ?? null,
                'date_delivered' => $validated['date_delivered'] ?? now(),
            ]);

            $tank = Tank::findOrFail($validated['tank_id']);
            $previous = $tank->status;
            $tank->status = 'with_customer';
            $tank->save();

            TankMovement::create([
                'tank_id' => $tank->id,
                'previous_status' => $previous,
                'new_status' => 'with_customer',
                'customer_id' => $validated['customer_id'],
                'driver_id' => $validated['driver_id'] ?? null,
                'created_at' => now(),
            ]);
        });

        return redirect()->route('tank.deliveries.index')->with('success', 'Tank delivery recorded');
    }

    /**
     * Show tank delivery map view
     */
    public function showMap(TankDelivery $tank_delivery)
    {
        $tank_delivery->load('tank', 'customer', 'driver');
        
        // Return JSON for AJAX or view for direct access
        if (request()->wantsJson()) {
            return response()->json([
                'id' => $tank_delivery->id,
                'tank' => $tank_delivery->tank,
                'customer' => $tank_delivery->customer,
                'driver' => $tank_delivery->driver,
                'date_delivered' => $tank_delivery->date_delivered,
                'created_at' => $tank_delivery->created_at,
            ]);
        }
        
        return view('tank_deliveries.map', ['delivery' => $tank_delivery]);
    }

    /**
     * Update delivery location during delivery
     */
    public function updateLocation(Request $request, TankDelivery $tank_delivery)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Store current driver location
        $tank_delivery->update([
            'driver_latitude' => $validated['latitude'],
            'driver_longitude' => $validated['longitude'],
        ]);

        return response()->json(['success' => true]);
    }
}
