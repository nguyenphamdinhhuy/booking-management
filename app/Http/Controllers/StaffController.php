<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class StaffController extends Controller
{
    public function index()
    {
        $staffs = User::where('role', 'staff')->get();
        return view('admin.staff.staff_management', compact('staffs'));
    }
    public function ajaxDetail($id)
    {
        $staff = User::findOrFail($id);
        return response()->json($staff);
    }
    public function edit($id)
    {
        $staff = User::findOrFail($id);
        return view('staff.edit', compact('staff'));
    }

    public function update(Request $request, $id)
    {
        $staff = User::findOrFail($id);
        $staff->update($request->only([
            'name',
            'email',
            'phone',
            'address',
            'gender',
            'dob',
            'position',
            'department',
            'contract_type',
            'salary',
        ]));

        if ($request->filled('password')) {
            $staff->password = Hash::make($request->password);
            $staff->save();
        }

        return redirect()->route('staff.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy($id)
    {
        $staff = User::findOrFail($id);
        $staff->delete();
        return redirect()->route('staff.index')->with('success', 'Xóa nhân viên thành công');
    }
}
