<?php
namespace App\Http\Controllers;
use App\Models\{Loan, Borrower, Payment};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoanController extends Controller {
    public function index() {
        // Auto-check Overdue Status
        Loan::where('status', 'Active')->where('due_date', '<', Carbon::today())->update(['status' => 'Overdue']);

        $loans = Loan::with('borrower')->latest()->get();
        $borrowers = Borrower::all();

        $stats = [
            'total_outstanding' => Loan::where('status', '!=', 'Completed')->sum('outstanding_balance'),
            'interest_earned' => Payment::sum('interest_added'),
            'active_count' => Loan::where('status', 'Active')->count(),
            'overdue_count' => Loan::where('status', 'Overdue')->count(),
        ];
        return view('dashboard', compact('loans', 'borrowers', 'stats'));
    }

    public function store(Request $request) {
        $adminID = 1; // Placeholder for admin ID, replace with Auth::id() in production
        Loan::create([
            'borrower_id' => $request->borrower_id,
            'principle_amount' => $request->amount,
            'interest_rate' => $request->rate,
            'release_date' => $request->release_date,
            'due_date' => $request->due_date,
            'outstanding_balance' => $request->amount,
            'admin_id' => $adminID,
        ]);
        return back()->with('success', 'Loan released!');
    }

    public function reports() {
        $overdueLoans = Loan::with('borrower')->where('status', 'Overdue')->get();
        $stats = [
            'total_principal' => Loan::sum('principle_amount'),
            'total_interest' => Payment::sum('interest_added'),
            'total_remaining' => Loan::sum('outstanding_balance'),
        ];
        return view('reports', compact('overdueLoans', 'stats'));
    }
}
