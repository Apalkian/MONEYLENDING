<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Tito's Lending</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-stats { border: none; border-radius: 12px; border-left: 5px solid; }
        .bg-mls { background-color: #00a65a; color: white; }
        body { background-color: #f8f9fa; }
    </style>
</head>
<body>

    <!-- NAVIGATION -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-success" href="#">MLS | MONEY LENDING</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('borrowers.index') }}">Borrowers</a></li>
                </ul>
                <div class="d-flex align-items-center">
                    @auth
                    <span class="text-light me-3 small">Admin: {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">Logout</button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- STATS SECTION (Figure 19) -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card card-stats p-3 shadow-sm border-primary bg-white text-dark">
                    <small class="text-muted fw-bold text-uppercase">Total Debt To Collect</small>
                    <h3 class="fw-bold">₱{{ number_format($stats['total_outstanding'], 2) }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats p-3 shadow-sm border-success bg-white text-dark">
                    <small class="text-muted fw-bold text-uppercase">Profit (Interest)</small>
                    <h3 class="fw-bold">₱{{ number_format($stats['interest_earned'], 2) }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats p-3 shadow-sm border-info bg-white text-dark">
                    <small class="text-muted fw-bold text-uppercase">Active Loans</small>
                    <h3 class="fw-bold">{{ $stats['active_count'] }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats p-3 shadow-sm border-danger bg-white text-dark">
                    <small class="text-muted fw-bold text-uppercase text-danger">Overdue Accounts</small>
                    <h3 class="fw-bold text-danger">{{ $stats['overdue_count'] }}</h3>
                </div>
            </div>
        </div>

        <!-- NEW LOAN FORM (With Live Calculation) -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom"><h6 class="mb-0 fw-bold text-success uppercase">Release New Cash Loan</h6></div>
            <div class="card-body">
                <form action="{{ route('loans.store') }}" method="POST" class="row g-2">
                    @csrf
                    <div class="col-md-2">
                        <label class="small fw-bold">Borrower</label>
                        <select name="borrower_id" class="form-select form-select-sm" required>
                            <option value="">Select Neighbor...</option>
                            @foreach($borrowers as $b)
                                <option value="{{$b->id}}">{{$b->first_name}} {{ $b->middle_name }} {{$b->last_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="small fw-bold">Loan Amount</label>
                        <input type="number" name="amount" id="loan_amount" placeholder="₱ Amount" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-1">
                        <label class="small fw-bold">Int %</label>
                        <input type="number" name="rate" id="interest_rate" placeholder="%" class="form-control form-control-sm" required>
                    </div>

                    <!-- Live Calculation Display -->
                    <div class="col-md-2">
                        <label class="small fw-bold text-primary">Total Payable</label>
                        <input type="text" id="total_payable_display" placeholder="₱ 0.00" class="form-control form-control-sm bg-light fw-bold text-primary" readonly>
                    </div>

                    <div class="col-md-2">
                        <label class="small fw-bold">Release Date</label>
                        <input type="date" name="release_date" value="{{ date('Y-m-d') }}" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-2">
                        <label class="small fw-bold text-danger">Due Date</label>
                        <input type="date" name="due_date" class="form-control form-control-sm" required>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-success btn-sm w-100 fw-bold">Disburse</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- LOAN LEDGER (Figure 20) -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom"><h6 class="mb-0 fw-bold text-dark uppercase">Active Loan Ledger ("As Of" Balance)</h6></div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th class="ps-3">Borrower Name</th>
                            <th>Loan Amount</th>
                            <th>Current Balance</th>
                            <th class="text-danger">Due Date</th>
                            <th>Status</th>
                            <th class="text-center">Collect Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $loan)
                        <tr class="bg-white">
                            <td class="ps-3 fw-bold">
                                {{ $loan->borrower->first_name }} {{ $loan->borrower->middle_name }} {{ $loan->borrower->last_name }}
                            </td>
                            <td>₱{{ number_format($loan->principle_amount, 2) }}</td>
                            <td class="fw-bold text-primary">₱{{ number_format($loan->outstanding_balance, 2) }}</td>
                                   <td class="{{ \Carbon\Carbon::parse($loan->due_date)->isPast() && $loan->status != 'Completed' ? 'text-danger fw-bold' : '' }}">
                        {{ \Carbon\Carbon::parse($loan->due_date)->format('M d, Y') }}
                    </td>
                            <td>
                                <span class="badge rounded-pill {{ $loan->status == 'Overdue' ? 'bg-danger' : ($loan->status == 'Completed' ? 'bg-secondary' : 'bg-success') }}">
                                    {{ $loan->status }}
                                </span>
                            </td>
                            <td>
                                @if($loan->status != 'Completed')
                                <form action="{{ route('payments.store') }}" method="POST" class="d-flex gap-1">
    @csrf
    <input type="hidden" name="loan_id" value="{{ $loan->id }}">
    <input type="number" name="amount_paid" placeholder="Pay" class="form-control form-control-sm" style="width: 80px;" required>
                                     <input type="number" name="interest_added" placeholder="+Add" class="form-control form-control-sm" style="width: 70px;">
    <button class="btn btn-sm btn-primary fw-bold">Record</button>
</form>
                                @else
                                    <div class="text-center small text-muted italic font-monospace">Account Settled</div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT FOR LIVE CALCULATION -->
    <script>
        const amountInput = document.getElementById('loan_amount');
        const rateInput = document.getElementById('interest_rate');
        const totalDisplay = document.getElementById('total_payable_display');

        function updateLiveTotal() {
            const amount = parseFloat(amountInput.value) || 0;
            const rate = parseFloat(rateInput.value) || 0;

            const interest = amount * (rate / 100);
            const total = amount + interest;

            totalDisplay.value = "₱ " + total.toLocaleString(undefined, {minimumFractionDigits: 2});
        }

        amountInput.addEventListener('input', updateLiveTotal);
        rateInput.addEventListener('input', updateLiveTotal);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
