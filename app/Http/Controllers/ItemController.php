<?php
namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        $totalItems = $items->count();
        $mostRecentItem = Item::orderBy('created_at', 'desc')->first();
        $itemsWithDates = Item::orderBy('created_at', 'desc')->get();
        return view('items.index', compact('items', 'totalItems', 'mostRecentItem', 'itemsWithDates'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand' => 'required|string|max:255',
            'size' => 'required|string|max:255',
            'amount' => 'required|integer',
        ]);

        Item::create($request->all());
        return redirect()->route('items.index')->with('success', 'Item added!');
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'brand' => 'required|string|max:255',
            'size' => 'required|string|max:255',
            'amount' => 'required|integer',
        ]);

        $item->update($request->all());
        return redirect()->route('items.index')->with('success', 'Item updated!');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted!');
    }

    public function hide(Item $item)
    {
        $this->authorizeManager();
        if (!$item->is_hidden) {
            $item->update(['is_hidden' => true]);
        }

        return back()->with('success', 'Item hidden!');
    }

    public function unhide(Item $item)
    {
        $this->authorizeManager();

        if ($item->is_hidden) {
            $item->update(['is_hidden' => false]);
        }

        return back()->with('success', 'Item unhidden!');
    }

    private function authorizeManager(): void
    {
        $user = auth()->user();
        abort_unless($user && $user->role === 'manager', 403, 'Forbidden');
    }
}