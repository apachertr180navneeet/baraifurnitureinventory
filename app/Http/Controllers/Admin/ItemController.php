<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{
    Item,
    Category,
};

use Mail,Hash,File,Auth,DB,Helper,Exception,Session,Redirect,Validator;
use Carbon\Carbon;

class ItemController extends Controller
{
    /**
     * Display the User index page.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $categories  = Category::where('status', 1)->get();
        // Pass the admin and comId to the view
        return view('admin.items.index', compact('categories'));
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

        $Category = Item::join('categories', 'items.category_id', '=', 'categories.id')
            ->select('items.*', 'categories.name as category_name')
            ->orderBy('items.id', 'desc')
            ->get();
        return response()->json(['data' => $Category]);
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
            $User = Item::findOrFail($request->userId);
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
            Item::where('id', $id)->delete();

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
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:items,code',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|integer|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        try {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                
                // Store image in public/uploads/items directory
                $image->move(public_path('uploads/items'), $imageName);
                
                // Generate full URL for the image
                $imageUrl = asset('uploads/items/' . $imageName);
                
                $item = Item::create([
                    'name' => $request->name,
                    'code' => $request->code,
                    'price' => $request->price,
                    'qty' => $request->quantity,
                    'image' => $imageUrl,
                    'category_id' => $request->category_id,
                    'status' => 1
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Item created successfully',
                    'data' => $item
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating item: ' . $e->getMessage()
            ]);
        }
    }

    // Fetch user data
    public function get($id)
    {
        $user = Item::find($id);
        return response()->json($user);
    }

    // Update user data
    public function update(Request $request)
    {
        // Validation rules
        $rules = [
            'name'        => 'required|string|max:255|unique:items,name,' . $request->id,
            'code'        => 'required|string|max:100|unique:items,code,' . $request->id,
            'category_id' => 'required|integer|exists:categories,id',
            'qty'         => 'required|numeric|min:0',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        // Validate request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ]);
        }

        $item = Item::find($request->id);

        if ($item) {
            // If new image is uploaded
            if ($request->hasFile('image')) {
                $file     = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path     = public_path('upload/items');

                // Create folder if not exists
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                // Move file to public/upload/items
                $file->move($path, $filename);

                // Save full URL in DB
                $fullUrl = asset('upload/items/' . $filename);
                $item->image = $fullUrl;
            }

            // Update other fields
            $item->name        = $request->name;
            $item->code        = $request->code;
            $item->category_id = $request->category_id;
            $item->qty         = $request->qty;
            $item->price       = $request->price;

            $item->save();

            return response()->json([
                'success' => true,
                'message' => 'Item Updated Successfully',
                'data'    => $item, // contains full URL in DB
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Item not found',
        ]);
    }

}
