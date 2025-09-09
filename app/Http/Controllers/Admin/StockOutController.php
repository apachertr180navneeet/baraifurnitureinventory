<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockOut;
use App\Models\StockMaster;
use Illuminate\Http\Request;

class StockOutController extends Controller
{
    public function index()
    {
        $stockOut = StockOut::with('item')->latest()->paginate(10);
        return view('admin.stock_out.index', compact('stockOut'));
    }

    public function create()
    {
        $items = StockMaster::all();
        return view('admin.stock_out.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:stock_master,id',
            'customer_name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:1',
        ]);

        $item = StockMaster::find($request->item_id);

        if ($item->qty < $request->qty) {
            return back()->with('error', 'Not enough stock available!');
        }

        $stockOut = StockOut::create($request->all());

        $item->qty -= $request->qty;
        $item->save();

        return redirect()->route('admin.stock-out.index')->with('success', 'Stock Out added successfully!');
    }

    public function edit(StockOut $stockOut)
    {
        $items = StockMaster::all();
        return view('admin.stock_out.edit', compact('stockOut', 'items'));
    }

    public function update(Request $request, StockOut $stockOut)
    {
        $request->validate([
            'item_id' => 'required|exists:stock_master,id',
            'customer_name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:1',
        ]);

        $item = StockMaster::find($request->item_id);
        $oldQty = $stockOut->qty;

        // restore old qty first
        $item->qty += $oldQty;

        if ($item->qty < $request->qty) {
            return back()->with('error', 'Not enough stock available!');
        }

        $stockOut->update($request->all());

        $item->qty -= $request->qty;
        $item->save();

        return redirect()->route('admin.stock-out.index')->with('success', 'Stock Out updated successfully!');
    }

    public function destroy(StockOut $stockOut)
    {
        $item = StockMaster::find($stockOut->item_id);
        $item->qty += $stockOut->qty;
        $item->save();

        $stockOut->delete();

        return redirect()->route('admin.stock-out.index')->with('success', 'Stock Out deleted successfully!');
    }
}
