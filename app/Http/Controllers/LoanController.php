<?php
namespace App\Http\Controllers;
use App\Models\{Loan, Borrower, Payment};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class LoanController extends Controller {

    // Updated Index to include stats for the dashboard as per PDF Figure 19
    public function index() {
        // Auto-check Overdue Status
        Loan::where('status', 'Active')
            ->where('due_date', '<', Carbon::today())
            ->update(['status' => 'Overdue']);

        $loans = Loan::with('borrower')->latest()->get();
        $borrowers = Borrower::all();
        // 1. Calculate Interest from Disbursement: Principal * (Rate / 100)
    $disbursedInterest = Loan::all()->sum(function($loan) {
        return $loan->principle_amount * ($loan->interest_rate / 100);
    });

    // 2. Calculate Extra Interest added later during manual payments
    $manualInterestAdded = Payment::sum('interest_added');

        $stats = [
            'total_outstanding' => Loan::where('status', '!=', 'Completed')->sum('outstanding_balance'),
            'interest_earned' => Payment::sum('interest_added'),
            'active_count' => Loan::where('status', 'Active')->count(),
            'overdue_count' => Loan::where('status', 'Overdue')->count(),
        ];
        return view('dashboard', compact('loans', 'borrowers', 'stats'));
    }

    public function store(Request $request) {
        $request->validate([
            'borrower_id' => 'required',
            'amount' => 'required|numeric',
            'rate' => 'required|numeric',
            'due_date' => 'required|date',
        ]);

        // Per PDF Page 3: Initial balance is Principal + initial interest
        $principal = $request->amount;
        $interest = $principal * ($request->rate / 100);

        $totalPayable = $principal + $interest;

        $adminID = Auth::id() ?? 1;

        Loan::create([
            'borrower_id'      => $request->borrower_id,
            'principle_amount' => $principal,
            'interest_rate'    => $request->rate,
            'release_date'     => $request->release_date ?? now(),
            'due_date'         => $request->due_date,
            'outstanding_balance' => $totalPayable,
            'status'           => 'Active',
            'admin_id'         => $adminID,
        ]);

        return back()->with('success', 'Loan disbursed!');
    }

    // NEW: Logic for Solution #10 (Additional Capital)
    public function addCapital(Request $request, Loan $loan) {
        $request->validate(['amount_added' => 'required|numeric']);

        DB::transaction(function () use ($request, $loan) {
            // Update the loan balance
            $loan->increment('outstanding_balance', $request->amount_added);

            // Record in Additional Capital table for the audit trail (Solution 10)
            \App\Models\AdditionalCapital::create([
                'loan_id' => $loan->id,
                'amount_added' => $request->amount_added,
                'date_added' => now(),
                'remarks' => $request->remarks ?? 'Additional capital added',
            ]);
        });

        return back()->with('success', 'Additional capital recorded!');
    }
}
