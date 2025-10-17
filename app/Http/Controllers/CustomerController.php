<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        $totalCustomers = $customers->count();

        return view('customers.index', compact('customers', 'totalCustomers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:customers,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer added!');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('customers', 'email')->ignore($customer->id),
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted!');
    }

    public function updateDropoff(Request $request, Customer $customer)
    {
        $this->authorizeManager();

        $validated = $request->validate([
            'dropoff_location' => ['nullable', 'string', 'max:1000'],
        ]);

        $customer->update($validated);

        return back()->with('success', 'Dropoff location updated.');
    }

    private function authorizeManager(): void
    {
        $user = Auth::user();
        abort_unless($user && $user->role === 'manager', 403, 'Forbidden');
    }
}