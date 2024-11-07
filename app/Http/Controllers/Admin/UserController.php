<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    public function list()
    {
        // List
        $users = User::query()->get();

        return view('admin.account', compact('users'));
    }
    public function destroy($id)
    {
        // Xóa mềm (ban)
        User::destroy($id);

        return redirect()->route('dashboard.account');
    }
    public function restore($id)
    {
        //Khôi phục (restore)
        User::withTrashed()->where('id', $id)->restore();

        return redirect()->route('dashboard.account');
    }
    public function setrole($id)
    {
        //Cấp quyền admin
        User::query()->where('id', $id)->update(['type' => User::TYPE_ADMIN]);

        return redirect()->route('dashboard.account');
    }
    public function downgrade($id)
    {
        // member
        User::query()->where('id', $id)->update(['type' => User::TYPE_MEMBER]);
        return redirect()->route('dashboard.account');
    }
    public function delete($id)
    {
        //xóa vĩnh viễn
        User::withTrashed()->where('id', $id)->forceDelete();

        return redirect()->route('dashboard.account');
    }
}
