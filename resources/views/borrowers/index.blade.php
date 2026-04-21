<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Borrowers - Tito's Lending</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- NAVIGATION (Same as Dashboard) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-success" href="{{ route('dashboard') }}">MLS | MONEY LENDING</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('borrowers.index') }}">Borrowers</a></li>
                </ul>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-sm btn-outline-danger">Logout</button></form>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row g-4">
            <!-- ADD BORROWER FORM -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white fw-bold">Add New Neighbor</div>
                    <div class="card-body">
                        <form action="{{ route('borrowers.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="small fw-bold">First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control" placeholder="Optional">
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold">Last Name</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold">Contact Number</label>
                                <input type="text" name="contact_number" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold">Full Address</label>
                                <textarea name="address" class="form-control" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Save Borrower</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- BORROWERS LIST (Figure 19) -->
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold">Community Members List</div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small">
                                <tr>
                                    <th>Full Name</th>
                                    <th>Contact</th>
                                    <th>Address</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($borrowers as $b)
                                <tr>
                                    <td class="fw-bold">{{ $b->first_name }} {{ $b->middle_name }} {{ $b->last_name }}</td>
                                    <td>{{ $b->contact_number }}</td>
                                    <td class="small text-muted">{{ $b->address }}</td>
                                    <td class="small">{{ \Carbon\Carbon::parse($b->date_registered)->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
