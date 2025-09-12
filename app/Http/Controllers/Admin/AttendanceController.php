<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{
    Attendance
};

use Mail,Hash,File,Auth,DB,Helper,Exception,Session,Redirect,Validator;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display the User index page.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Pass the admin and comId to the view
        return view('admin.attendance.index');
    }

    /**
     * Fetch all companies and return as JSON.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getall(Request $request)
    {
        $user = Auth::user();

        $compId = $user->admin_id;

        $attendance = Attendance::orderBy('id', 'desc')
            ->get();
        return response()->json(['data' => $attendance]);
    }

    /**
     * Update the status of a User.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Request $request)
    {
        try {
            $User = Attendance::findOrFail($request->userId);
            $User->status = $request->status;
            $User->save();

            return response()->json(['success' => true]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Delete a User by its ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            Attendance::where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date'         => 'required|date',
            'name'         => 'required|string|max:255',
            'in_time'      => 'nullable|string|max:50',
            'out_time'     => 'nullable|string|max:50',
            'leave_day'    => 'nullable|string|max:50',
            'leave_reason' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ]);
        }

        try {
            $attendance = Attendance::create([
                'date'         => $request->date,
                'name'         => $request->name,
                'in_time'      => $request->in_time,
                'out_time'     => $request->out_time,
                'leave_day'    => $request->leave_day,
                'leave_reason' => $request->leave_reason,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Attendance created successfully',
                'data'    => $attendance
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating attendance: ' . $e->getMessage()
            ]);
        }
    }


    // Fetch user data
    public function get($id)
    {
        $user = Attendance::find($id);
        return response()->json($user);
    }

    // Update user data
    public function update(Request $request)
    {
        // Validation rules
        $rules = [
            'name' => 'required|string|max:255|unique:items,name,' . $request->id,
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $user = Attendance::find($request->id);
        if ($user) {
            $user->update($request->all());
            return response()->json(['success' => true , 'message' => 'Category Update Successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Category not found']);
    }
}
