<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EducatorController extends Controller
{
    public function index()
    {
        // Fetch all users with the 'educator' role
        $educators = User::where('role', 'educator')->orderBy('name', 'asc')->paginate(10);// Sort by name; // Paginate results

        return view('educator.index', compact('educators'));
    }

    public function destroy($id)
    {
        $educator = User::where('role', 'educator')->findOrFail($id);
        $educator->delete();

        return redirect()->route('educator.index')->with('success', 'Educator deleted successfully.');
    }
    public function edit($id)
    {
        $educator = User::where('role', 'educator')->findOrFail($id);
        return view('educator.edit', compact('educator'));
    }

    public function update(Request $request, $id)
    {
        // Find the educator by ID
        $educator = User::where('role', 'educator')->findOrFail($id);

        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $educator->id,
        ]);

        // Update the educator's data
        $educator->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Redirect to the educator index page with a success message
        return redirect()->route('educator.index')->with('success', 'Educator information updated successfully.');
    }

}
