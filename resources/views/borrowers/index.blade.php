<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Borrowers - Tito's Lending</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- NAVIGATION -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-success" href="{{ route('dashboard') }}">MLS | MONEY LENDING</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('borrowers.index') }}">Borrowers</a></li>
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

            <!-- BORROWERS LIST -->
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
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($borrowers as $b)
                                <tr>
                                    <td class="fw-bold">{{ $b->first_name }} {{ $b->middle_name }} {{ $b->last_name }}</td>
                                    <td>{{ $b->contact_number }}</td>
                                    <td class="small text-muted">{{ $b->address }}</td>
                                    <td class="text-center">
                                        <!-- Edit Button triggers Edit Modal -->
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $b->id }}">Edit</button>

                                        <!-- Delete Button triggers Delete Modal -->
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $b->id }}">Delete</button>
                                    </td>
                                </tr>

                                <!-- EDIT MODAL -->
                                <div class="modal fade" id="editModal{{ $b->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">Edit Borrower Info</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('borrowers.update', $b->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">First Name</label>
                                                        <input type="text" name="first_name" value="{{ $b->first_name }}" class="form-control" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Middle Name</label>
                                                        <input type="text" name="middle_name" value="{{ $b->middle_name }}" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Last Name</label>
                                                        <input type="text" name="last_name" value="{{ $b->last_name }}" class="form-control" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Contact Number</label>
                                                        <input type="text" name="contact_number" value="{{ $b->contact_number }}" class="form-control" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Address</label>
                                                        <textarea name="address" class="form-control" rows="2">{{ $b->address }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Update Borrower</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- DELETE MODAL -->
                                <div class="modal fade" id="deleteModal{{ $b->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                            </div>
                                            <div class="modal-body text-center">
                                                Are you sure you want to delete <br> <strong>{{ $b->first_name }} {{ $b->last_name }}</strong>?
                                                <p class="text-danger small mt-2">All their loan records will also be removed.</p>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <form action="{{ route('borrowers.destroy', $b->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle (Required for Modals) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
