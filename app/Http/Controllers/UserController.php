<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('user.users');
    }

    public function get(Request $request)
    {
        $users = User::select(['id', 'name', 'email'])->get();
        return response()->json(['success' => true, 'data' => $users]);
    }

    public function delete(User $user)
    {
        $user->companies()->delete();
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User Deleted Successfully']);
    }

    public function store(UserStoreRequest $request)
    {
        $isEdit = $request->has('id');
        $user = $isEdit ? User::find($request->id) : new User();
        $user->name = $request->name;
        $user->email = $request->email;
        if (!$isEdit) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        return response()->json(['success' => true, 'message' => 'User Details Saved Successfully']);
    }


}
