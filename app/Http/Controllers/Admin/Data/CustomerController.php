<?php

namespace App\Http\Controllers\Admin\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.data.customers');
    }

    public function getData(Request $request)
    {
        $customers = User::where('role', 'customer')
            ->join('customers', 'users.id', '=', 'customers.user_id')
            ->select([
                'users.id',
                'users.name',
                'users.username',
                'users.email',
                'customers.phone',
                'customers.address',
                'customers.latitude',
                'customers.longitude',
                'users.created_at'
            ]);

        return DataTables::of($customers)
            ->addColumn('action', function ($row) {
                return '
                    <button type="button" class="btn btn-info btn-sm view-btn" data-id="' . $row->id . '">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button type="button" class="btn btn-primary btn-sm edit-btn" data-id="' . $row->id . '">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '">
                        <i class="fas fa-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getCustomer($id)
    {
        $customer = User::where('role', 'customer')
            ->where('users.id', $id)
            ->join('customers', 'users.id', '=', 'customers.user_id')
            ->select([
                'users.id',
                'users.name',
                'users.username',
                'users.email',
                'customers.phone',
                'customers.address',
                'customers.latitude',
                'customers.longitude'
            ])
            ->first();

        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        return response()->json($customer);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'customer',
            ]);

            Customer::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            DB::commit();

            return response()->json(['success' => 'Customer created successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create customer: ' . $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user || $user->role !== 'customer') {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($id),
            ],
            'password' => 'nullable|string|min:6',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            $customer = Customer::where('user_id', $id)->first();
            $customer->phone = $request->phone;
            $customer->address = $request->address;
            $customer->latitude = $request->latitude;
            $customer->longitude = $request->longitude;
            $customer->save();

            DB::commit();

            return response()->json(['success' => 'Customer updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update customer: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user || $user->role !== 'customer') {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        try {
            $user->delete();

            return response()->json(['success' => 'Customer deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete customer: ' . $e->getMessage()], 500);
        }
    }
}
