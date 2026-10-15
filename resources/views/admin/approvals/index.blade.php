@extends('layouts.admin')

@section('content')
<div class="card border-0 shadow-sm mb-4" style="border-radius: 0.75rem;">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="fa-solid fa-store me-2 text-primary"></i> Pending Vendor Approvals</h5>
        
        @if($pendingVendors->isEmpty())
            <p class="text-muted">No pending vendor approvals.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Business Name</th>
                            <th>Mobile</th>
                            <th>Document</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingVendors as $vendor)
                        <tr>
                            <td>
                                <strong>{{ $vendor->restaurant_name }}</strong><br>
                                <small class="text-muted">{{ $vendor->user->name ?? 'N/A' }}</small>
                            </td>
                            <td>{{ $vendor->mobile }}</td>
                            <td>
                                @if($vendor->document_path)
                                    <button type="button" onclick="previewDocument('{{ asset('storage/' . $vendor->document_path) }}')" class="btn btn-sm btn-outline-info">
                                        <i class="fa-solid fa-eye"></i> View Doc
                                    </button>
                                @else
                                    <span class="text-muted">No Document</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('admin.approvals.approve', ['type' => 'vendor', 'id' => $vendor->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success"><i class="fa-solid fa-check"></i> Approve</button>
                                    </form>
                                    <form action="{{ route('admin.approvals.reject', ['type' => 'vendor', 'id' => $vendor->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-xmark"></i> Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 0.75rem;">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="fa-solid fa-id-card me-2 text-primary"></i> Pending Driver Approvals</h5>
        
        @if($pendingDrivers->isEmpty())
            <p class="text-muted">No pending driver approvals.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Driver Name</th>
                            <th>Mobile</th>
                            <th>Document</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingDrivers as $driver)
                        <tr>
                            <td>
                                <strong>{{ $driver->name }}</strong><br>
                                <small class="text-muted">{{ $driver->user->name ?? 'N/A' }}</small>
                            </td>
                            <td>{{ $driver->mobile }}</td>
                            <td>
                                @if($driver->document_path)
                                    <button type="button" onclick="previewDocument('{{ asset('storage/' . $driver->document_path) }}')" class="btn btn-sm btn-outline-info">
                                        <i class="fa-solid fa-eye"></i> View Doc
                                    </button>
                                @else
                                    <span class="text-muted">No Document</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('admin.approvals.approve', ['type' => 'driver', 'id' => $driver->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success"><i class="fa-solid fa-check"></i> Approve</button>
                                    </form>
                                    <form action="{{ route('admin.approvals.reject', ['type' => 'driver', 'id' => $driver->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-xmark"></i> Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Document Preview Modal -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentModalLabel"><i class="fa-solid fa-file-lines me-2 text-primary"></i> Document Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="height: 75vh;">
                <iframe id="documentPreviewFrame" src="" style="width: 100%; height: 100%; border: none; border-bottom-left-radius: 0.3rem; border-bottom-right-radius: 0.3rem;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    function previewDocument(url) {
        document.getElementById('documentPreviewFrame').src = url;
        var docModal = new bootstrap.Modal(document.getElementById('documentModal'));
        docModal.show();
    }
    
    // Clear iframe src when modal is hidden to stop any background loading or audio/video if applicable
    document.addEventListener('DOMContentLoaded', function() {
        var myModalEl = document.getElementById('documentModal');
        myModalEl.addEventListener('hidden.bs.modal', function (event) {
            document.getElementById('documentPreviewFrame').src = '';
        });
    });
</script>
@endsection
