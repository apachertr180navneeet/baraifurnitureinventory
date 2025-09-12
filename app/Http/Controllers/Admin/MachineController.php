<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Machine;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Exception;

class MachineController extends Controller
{
    /**
     * Display index page.
     */
    public function index(Request $request)
    {
        return view('admin.machine.index'); // ✅ change to machine view
    }

    /**
     * Fetch all machines (for DataTables).
     */
    public function getall(Request $request)
    {
        $machines = Machine::orderBy('id', 'desc')->get();
        return response()->json(['data' => $machines]);
    }

    /**
     * Store new machine.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255|unique:machines,name',
            'remark' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ]);
        }

        try {
            $machine = Machine::create([
                'name'   => $request->name,
                'remark' => $request->remark,
                'status' => 'active', // default
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Machine created successfully',
                'data'    => $machine
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating machine: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get machine by ID (for edit).
     */
    public function get($id)
    {
        $machine = Machine::find($id);
        return response()->json($machine);
    }

    /**
     * Update machine.
     */
    public function update(Request $request)
    {
        $rules = [
            'name'   => 'required|string|max:255|unique:machines,name,' . $request->id,
            'remark' => 'nullable|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $machine = Machine::find($request->id);
        if ($machine) {
            $machine->update([
                'name'   => $request->name,
                'remark' => $request->remark,
            ]);
            return response()->json(['success' => true , 'message' => 'Machine updated successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Machine not found']);
    }

    /**
     * Update status.
     */
    public function status(Request $request)
    {
        try {
            $machine = Machine::findOrFail($request->userId);
            $machine->status = $request->status;
            $machine->save();

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Delete machine.
     */
    public function destroy($id)
    {
        try {
            Machine::where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Machine deleted successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
