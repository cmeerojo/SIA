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
            'type' => ['required', Rule::in(['customer','business'])],
            'business_name' => ['nullable','string','max:255','required_if:type,business'],
            'name'  => ['nullable', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255', 'unique:customers,email', 'required_if:type,business'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'dropoff_street' => ['required', 'string', 'max:255'],
            'dropoff_city' => ['required', 'string', 'max:255'],
            'dropoff_landmark' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:100'],
            'reorder_point' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        // Map names based on type: business -> use business_name; customer -> use first/middle/last
        if (($validated['type'] ?? 'customer') === 'business') {
            $validated['name'] = trim((string)($validated['business_name'] ?? ''));
            // Clear personal name parts for businesses
            $validated['first_name'] = $validated['first_name'] ?? null;
            $validated['middle_name'] = $validated['middle_name'] ?? null;
            $validated['last_name'] = $validated['last_name'] ?? null;
        } else {
            // If first/last provided, use them; otherwise try to split legacy name
            if (empty($validated['first_name']) && !empty($validated['name'])) {
                $parts = preg_split('/\s+/', trim($validated['name']));
                $validated['first_name'] = $parts[0] ?? null;
                $validated['last_name'] = count($parts) > 1 ? array_pop($parts) : null;
                if (count($parts) > 1) {
                    $validated['middle_name'] = implode(' ', array_slice($parts, 1, count($parts)-1));
                }
            }
            // Ensure legacy `name` is present (for backward compatibility)
            $validated['name'] = trim(sprintf('%s %s %s', $validated['first_name'] ?? '', $validated['middle_name'] ?? '', $validated['last_name'] ?? ''));
        }

        // Build combined dropoff_location for backward compatibility (include landmark when present)
        $parts = [];
        if (!empty($validated['dropoff_street'] ?? null)) $parts[] = $validated['dropoff_street'];
        if (!empty($validated['dropoff_landmark'] ?? null)) $parts[] = $validated['dropoff_landmark'];
        if (!empty($validated['dropoff_city'] ?? null)) $parts[] = $validated['dropoff_city'];
        $validated['dropoff_location'] = trim(implode(', ', $parts));

        unset($validated['business_name']);

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
            'type' => ['required', Rule::in(['customer','business'])],
            'business_name' => ['nullable','string','max:255','required_if:type,business'],
            'name'  => ['nullable', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('customers', 'email')->ignore($customer->id),
                'required_if:type,business',
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'dropoff_street' => ['required', 'string', 'max:255'],
            'dropoff_city' => ['required', 'string', 'max:255'],
            'dropoff_landmark' => ['nullable', 'string', 'max:255'],
            'contact_number' => ['nullable', 'string', 'max:100'],
            'reorder_point' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        if (($validated['type'] ?? 'customer') === 'business') {
            $validated['name'] = trim((string)($validated['business_name'] ?? ''));
        } else {
            if (empty($validated['first_name']) && !empty($validated['name'])) {
                $parts = preg_split('/\s+/', trim($validated['name']));
                $validated['first_name'] = $parts[0] ?? null;
                $validated['last_name'] = count($parts) > 1 ? array_pop($parts) : null;
                if (count($parts) > 1) {
                    $validated['middle_name'] = implode(' ', array_slice($parts, 1, count($parts)-1));
                }
            }
            $validated['name'] = trim(sprintf('%s %s %s', $validated['first_name'] ?? $customer->first_name ?? '', $validated['middle_name'] ?? $customer->middle_name ?? '', $validated['last_name'] ?? $customer->last_name ?? ''));
        }

        // Build combined dropoff_location for backward compatibility (include landmark when present)
        $street = $validated['dropoff_street'] ?? $customer->dropoff_street ?? '';
        $landmark = $validated['dropoff_landmark'] ?? $customer->dropoff_landmark ?? '';
        $city = $validated['dropoff_city'] ?? $customer->dropoff_city ?? '';
        $parts = [];
        if (!empty($street)) $parts[] = $street;
        if (!empty($landmark)) $parts[] = $landmark;
        if (!empty($city)) $parts[] = $city;
        $validated['dropoff_location'] = trim(implode(', ', $parts));

        unset($validated['business_name']);

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
            'dropoff_street' => ['required', 'string', 'max:255'],
            'dropoff_city' => ['required', 'string', 'max:255'],
            'dropoff_landmark' => ['nullable', 'string', 'max:255'],
        ]);

        // Include landmark for better geocoding
        $parts = [];
        if (!empty($validated['dropoff_street'] ?? null)) $parts[] = $validated['dropoff_street'];
        if (!empty($validated['dropoff_landmark'] ?? null)) $parts[] = $validated['dropoff_landmark'];
        if (!empty($validated['dropoff_city'] ?? null)) $parts[] = $validated['dropoff_city'];
        $validated['dropoff_location'] = trim(implode(', ', $parts));

        $customer->update($validated);

        return back()->with('success', 'Dropoff location updated.');
    }

    private function authorizeManager(): void
    {
        $user = Auth::user();
        abort_unless($user && $user->role === 'manager', 403, 'Forbidden');
    }
}