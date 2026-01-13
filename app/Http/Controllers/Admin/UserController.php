<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    
    public function list()
    {
        // List all users including soft deleted ones
        $users = User::withTrashed()->get();

        return view('admin.account', compact('users'));
    }

    public function restore($id)
    {
        // Khôi phục (restore)
        User::withTrashed()->where('id', $id)->restore();

        return redirect()->route('dashboard.account')->with('success', 'Tài khoản đã được khôi phục thành công!');
    }

    public function setrole($id)
    {
        // Cấp quyền admin
        User::query()->where('id', $id)->update(['type' => User::TYPE_ADMIN]);

        return redirect()->route('dashboard.account')->with('success', 'Đã cấp quyền admin thành công!');
    }

    public function downgrade($id)
    {
        // Chuyển về member
        User::query()->where('id', $id)->update(['type' => User::TYPE_MEMBER]);
        
        return redirect()->route('dashboard.account')->with('success', 'Đã chuyển về quyền member thành công!');
    }

    public function toggleStatus($id)
    {
        // Toggle active status instead of delete
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'kích hoạt' : 'vô hiệu hóa';
        return redirect()->route('dashboard.account')->with('success', "Đã {$status} tài khoản thành công!");
    }
}
