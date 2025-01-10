<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserManagementController extends Controller
{
    /** User List */
    public function userList()
    {
        return view('usermanagement.listuser');
    }

    /** Add New Users */
    public function userAddNew()
    {
        return view('usermanagement.useraddnew');
    }

    /** Save New User */
    public function saveUser(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validasi input
            $request->validate([
                'user_id' => 'required|string|max:20|unique:users,user_id',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone_number' => 'required|string|max:15',
                'role_name' => 'required|string|in:admin,user',
                'division' => 'required|string',
                'department' => 'required|string|max:50',
                'profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            // Handle file upload
            $avatarName = null;
            if ($request->hasFile('profile')) {
                $avatar = $request->file('profile');
                $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
                // Store directly in public/uploads/avatar directory
                $avatar->move(public_path('uploads/avatar'), $avatarName);
            }

            // Create new user
            User::create([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'role_name' => $request->role_name,
                'division' => $request->division,
                'department' => $request->department,
                'avatar' => $avatarName,
                'status' => 'Active',
                'join_date' => now()->format('Y-m-d'),
            ]);

            DB::commit();
            flash()->success('User added successfully :)');
            return redirect()->route('user/list');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Failed to add user: ' . $e->getMessage());
            Log::error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /** View Record */
    public function userView($user_id)
    {
        $userData = User::where('user_id', $user_id)->first();
        return view('usermanagement.useredit', compact('userData'));
    }

    /** Update Record */
    public function userUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            $updateRecord = [
                'name'         => $request->name,
                'email'        => $request->email,
                'phone_number' => $request->phone_number,
                'division'     => $request->division,
                'department'   => $request->department,
            ];
            User::where('user_id', $request->user_id)->update($updateRecord);

            DB::commit();
            flash()->success('Updated record successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('Update record fail :)');
            Log::error($e->getMessage());
            return redirect()->back();
        }
    }

    /** Delete Record */
    public function userDelete($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return redirect()->back()->with('error', 'User not found!');
            }

            // Simpan nama user untuk pesan
            $userName = $user->name;

            $user->delete();
            return redirect()->back()->with('success', "User '{$userName}' has been deleted successfully.");
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete user. Please try again.');
        }
    }

    /** Get Users Data */
    public function getUsersData(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowPerPage = $request->get("length");
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column'];
        $columnName = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir'];
        $searchValue = $search_arr['value'];

        $users = DB::table('users');
        $totalRecords = $users->count();

        $totalRecordsWithFilter = $users->where(function ($query) use ($searchValue) {
            $query->where('name', 'like', '%' . $searchValue . '%');
            $query->orWhere('email', 'like', '%' . $searchValue . '%');
            $query->orWhere('division', 'like', '%' . $searchValue . '%');
            $query->orWhere('department', 'like', '%' . $searchValue . '%');
            $query->orWhere('phone_number', 'like', '%' . $searchValue . '%');
            $query->orWhere('status', 'like', '%' . $searchValue . '%');
        })->count();

        $records = $users->orderBy($columnName, $columnSortOrder)
            ->where(function ($query) use ($searchValue) {
                $query->where('name', 'like', '%' . $searchValue . '%');
                $query->orWhere('email', 'like', '%' . $searchValue . '%');
                $query->orWhere('division', 'like', '%' . $searchValue . '%');
                $query->orWhere('department', 'like', '%' . $searchValue . '%');
                $query->orWhere('phone_number', 'like', '%' . $searchValue . '%');
                $query->orWhere('status', 'like', '%' . $searchValue . '%');
            })
            ->skip($start)
            ->take($rowPerPage)
            ->get();

        $data_arr = [];

        foreach ($records as $key => $record) {
            $modify = '
                <td class="text-right">
                    <div class="dropdown dropdown-action">
                        <a href="" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v ellipse_color"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="' . url('users/add/edit/' . $record->user_id) . '">
                                <i class="fas fa-pencil-alt m-r-5"></i> Edit
                            </a>
                            <a class="dropdown-item" href="' . url('users/delete/' . $record->id) . '">
                                <i class="fas fa-trash-alt m-r-5"></i> Delete
                            </a>
                        </div>
                    </div>
                </td>
            ';

            $data_arr[] = [
                "user_id"      => $record->user_id,
                "name"         => $record->name,
                "email"        => $record->email,
                "phone_number" => $record->phone_number,
                "division"     => $record->division,
                "department"   => $record->department,
                "status"       => $record->status,
                "modify"       => $modify,
            ];
        }

        $response = [
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordsWithFilter,
            "aaData"              => $data_arr
        ];

        return response()->json($response);
    }
}
