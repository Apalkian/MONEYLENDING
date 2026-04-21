<?php
namespace App\Http\Controllers;
use App\Models\{Payment, Loan};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller {
    public function store(Request $request) {
        DB::transaction(function () use ($request) {
            $loan = Loan::findOrFail($request->loan_id);
            $interest = $request->interest_added ?? 0;
            // PDF Page 3 Logic: (Current + Interest) - Payment
            $newBalance = ($loan->outstanding_balance + $interest) - $request->amount_paid;

            Payment::create([
                'loan_id' => $loan->id,
                'payment_date' => now(),
                'amount_paid' => $request->amount_paid,
                'interest_added' => $interest,
                'new_balance' => max(0, $newBalance),
                'admin_id' => Auth::id(),
            ]);

            $loan->update([
                'outstanding_balance' => max(0, $newBalance),
                'status' => $newBalance <= 0 ? 'Completed' : $loan->status
            ]);
        });
        return back();
    }
}
