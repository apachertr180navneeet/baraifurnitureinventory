<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{
    Manufacturing,
    Item,
    Vendor,
    ManufacturingItem,
};
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class ManufacturingController extends Controller
{
    public function index(Request $request)
    {
        $vendors = Vendor::where('status', 1)->get();
        $items = Item::where('status', 1)->get();
        return view('admin.manufacturing.index', compact('vendors', 'items'));
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
        $manufacturings = Manufacturing::with(['item', 'manufacturingItem.item'])
            ->orderBy('id', 'desc')
            ->get();

        $data = [];

        foreach ($manufacturings as $manufacturing) {
            foreach ($manufacturing->manufacturingItem as $item) {
                $data[] = [
                    'id'          => $manufacturing->id,
                    'item_name'   => $manufacturing->item ? $manufacturing->item->name : '-',
                    'start_date'  => $manufacturing->start_date,
                    'end_date'    => $manufacturing->end_date,
                    'qty'         => $manufacturing->qty,
                    'add_amount'  => $manufacturing->add_amount,
                    'status'      => $manufacturing->status,
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
            // -------- Image Upload (public/upload/manufacturing) --------
            $imageUrl = null;
            if ($request->hasFile('product_image')) {
                $file     = $request->file('product_image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path     = public_path('upload/manufacturing');

                // Create folder if not exists
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                // Move file
                $file->move($path, $filename);

                // Full URL
                $imageUrl = asset('upload/manufacturing/' . $filename);
            }

            // -------- Save manufacturing --------
            $manufacturing = Manufacturing::create([
                'item_id'    => $request->item_id,
                'start_date' => $request->start_date,
                'end_date'   => $request->end_date,
                'qty'        => $request->qty,
                'add_amount' => $request->add_amount,
                'image'      => $imageUrl, // full URL
            ]);

            // -------- Finished Product Stock Increase --------
            $finishedItem = Item::find($request->item_id);
            if ($finishedItem) {
                $finishedItem->qty += $request->qty; // increase stock
                $finishedItem->save();
            }

            // -------- Raw Material Stock Deduction --------
            if ($request->has('materials')) {
                foreach ($request->materials as $material) {
                    ManufacturingItem::create([
                        'manufacturing_id' => $manufacturing->id,
                        'item_id'          => $material['material_id'],
                        'qty'              => $material['qty'],
                    ]);

                    $materialItem = Item::find($material['material_id']);
                    if ($materialItem) {
                        $materialItem->qty -= $material['qty']; // deduct stock
                        if ($materialItem->qty < 0) {
                            throw new \Exception("Not enough stock for material: {$materialItem->name}");
                        }
                        $materialItem->save();
                    }
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Manufacturing created successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
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
            $stockIn = Manufacturing::findOrFail($request->stockId);

            // Update status
            $stockIn->status = $request->status;
            $stockIn->save();


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

            $stockIn = Manufacturing::findOrFail($id);

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
