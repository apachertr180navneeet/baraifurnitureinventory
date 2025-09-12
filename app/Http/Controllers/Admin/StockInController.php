<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{
    StockIn,
    Item,
    Vendor,
    StockInItem,
};
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function index(Request $request)
    {
        $vendors = Vendor::where('status', 1)->get();
        $items = Item::where('status', 1)->get();
        return view('admin.stock_in.index', compact('vendors', 'items'));
    }

    /**
     * Fetch all Stock In records with items and return as JSON.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        // Eager load vendor and items (with item details)
        $stockIns = StockIn::with(['vendor', 'stockInItems.item'])
            ->orderBy('id', 'desc')
            ->get();

        $data = [];

        foreach ($stockIns as $stockIn) {
            foreach ($stockIn->stockInItems as $item) {
                $data[] = [
                    'id'          => $stockIn->id,
                    'vendor_name' => $stockIn->vendor ? $stockIn->vendor->name : '-',
                    'item_name'   => $item->item ? $item->item->name : '-',
                    'rate'        => number_format($item->price, 2),
                    'qty'         => $item->qty,
                    'total_amount'=> number_format($item->total, 2),
                    'status'      => $stockIn->status,
                    'action'      => '', // JS will render action buttons
                ];
            }
        }

        return response()->json([
            'draw'            => intval($request->draw), // required by DataTables
            'recordsTotal'    => count($data),           // total rows before filtering
            'recordsFiltered' => count($data),           // total rows after filtering
            'data'            => $data                   // actual table data
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // ✅ Save StockIn first
            $stockIn = StockIn::create([
                'date'      => $request->date,
                'vendor_id' => $request->vendor_id,
                'status'    => 'active',
            ]);

            // ✅ Save StockIn Items
            if ($request->has('items') && count($request->items) > 0) {
                foreach ($request->items as $item) {
                    StockInItem::create([
                        'stock_in_id' => $stockIn->id,
                        'item_id'     => $item['item_id'],
                        'qty'         => $item['qty'],
                        'price'       => $item['rate'],
                        'total'       => $item['total'],
                    ]);

                    // Update stock in the Item table
                    $stockItem = Item::find($item['item_id']);
                    if ($stockItem) {
                        $stockItem->qty += $item['qty']; // add new quantity
                        $stockItem->save();
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock In saved successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the status of a Stock In record.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Request $request)
    {
        DB::beginTransaction(); // Start transaction
        try {
            // Find the StockIn record
            $stockIn = StockIn::findOrFail($request->stockId);

            // Update status
            $stockIn->status = $request->status;
            $stockIn->save();


            $stockInItem = StockInItem::where('stock_in_id',$request->stockId)->get();

            // If status is 'completed' (or any status you want), reduce item quantities
            if ($request->status == 'inactive') {
                foreach ($stockInItem as $stockItem) {
                    // Assuming you have a relation StockIn hasMany StockInItem as 'items'
                    // and StockInItem has item_id and qty
                    $item = Item::find($stockItem->item_id);
                    if ($item) {
                        // Reduce the qty
                        $item->qty = $item->qty - $stockItem->qty;
                        $item->save();
                    }
                }
            }else{
                foreach ($stockInItem as $stockItem) {
                    // Assuming you have a relation StockIn hasMany StockInItem as 'items'
                    // and StockInItem has item_id and qty
                    $item = Item::find($stockItem->item_id);
                    if ($item) {
                        // Incresse the qty
                        $item->qty = $item->qty + $stockItem->qty;
                        $item->save();
                    }
                }
            }

            DB::commit(); // Commit transaction

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            dd($e); // For debugging
            return response()->json([
                'success' => false,
                'message' => 'Stock In record not found or error occurred'
            ], 404);
        }
    }

    /**
     * Delete a Stock In record by its ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction(); // Start transaction
        try {
            
            $stockIn = StockIn::findOrFail($id);

            $stockInItem = StockInItem::where('stock_in_id',$id)->get();

            // Loop through each related StockInItem
            foreach ($stockInItem as $stockItem) {
                $item = Item::find($stockItem->item_id);
                if ($item) {
                    // Reduce the quantity from the item
                    $item->qty = $item->qty - $stockItem->qty;

                    // Optional: prevent negative stock
                    if ($item->qty < 0) {
                        $item->qty = 0;
                    }

                    $item->save();
                }
            }

            // Delete the StockIn record
            $stockIn->delete();

            DB::commit(); // Commit transaction

            return response()->json([
                'success' => true,
                'message' => 'Stock In record deleted and item quantities updated successfully'
            ]);

        } catch (\Exception $e) {
            dd($e);
            DB::rollBack(); // Rollback on error
            return response()->json([
                'success' => false,
                'message' => 'Stock In record not found or error occurred'
            ], 404);
        }
    }
}
