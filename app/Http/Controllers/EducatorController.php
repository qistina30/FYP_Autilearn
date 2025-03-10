<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EducatorController extends Controller
{
    public function index()
    {
        // Fetch all users with the 'educator' role
        $educators = User::where('role', 'educator')->paginate(10); // Paginate results

        return view('educator.index', compact('educators'));
    }

    public function destroy($id)
    {
        $educator = User::where('role', 'educator')->findOrFail($id);
        $educator->delete();

        return redirect()->route('educator.index')->with('success', 'Educator deleted successfully.');
    }

}
