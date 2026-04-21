<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowerController extends Controller
{
    public function index() {
        $borrowers = Borrower::latest()->get();
        return view('borrowers.index', compact('borrowers'));
    }

    public function store(Request $request) {
    $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'contact_number' => 'required',
    ]);

        Borrower::create([
        'first_name' => $request->first_name,
        'middle_name' => $request->middle_name, // Save Middle Name
        'last_name' => $request->last_name,
        'contact_number' => $request->contact_number,
        'address' => $request->address,
        'date_registered' => now(),
        'admin_id' => Auth::id(),
    ]);

    return back()->with('success', 'Borrower added!');
}
}
