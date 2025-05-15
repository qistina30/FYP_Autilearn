<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('user_id', '!=', 1)  // Exclude super admin
        ->orderBy('name', 'asc') // Order by full name in ascending order
        ->paginate(10); // Paginate with 10 users per page
        return view('admin.index', compact('users'));
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $users = User::where('user_id', '!=', 1)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                    ->orWhere('email', 'like', "%$query%")
                ->orWhere('user_id', 'like', "%$query%");
            })
            ->paginate(10);

        return view('admin.partials.user-table', compact('users'))->render(); // optional partial
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            // do NOT update role here since it's fixed
        ]);

        return redirect()->route('admin.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.index')->with('success', 'User deleted successfully.');
    }

}
