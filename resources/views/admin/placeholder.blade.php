@extends('layouts.admin')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title fs-3 fw-bold mb-1" style="color: var(--text-main);">{{ $title ?? 'Coming Soon' }}</h1>
        <div class="breadcrumb-custom text-muted fs-6" style="font-size: 0.85rem;">
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Home</a> <span class="mx-2">&gt;</span> {{ $title ?? 'Module' }}
        </div>
    </div>
</div>

<div class="row justify-content-center mt-5">
    <div class="col-md-8 col-lg-6 text-center">
        <div class="glass-card p-5" style="border-radius: 16px;">
            <div class="mb-4">
                <div style="width: 80px; height: 80px; background: #e0e7ff; color: #6366f1; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 2rem;">
                    <i class="fa-solid fa-rocket"></i>
                </div>
            </div>
            <h2 class="fw-bold mb-3" style="color: var(--text-main);">Module In Development</h2>
            <p class="text-muted mb-4" style="line-height: 1.6; font-size: 0.95rem;">
                The <strong>{{ $title ?? 'requested' }}</strong> module is currently being built. This functionality will be available in the upcoming platform release.
            </p>
            <a href="{{ route('admin.dashboard') }}" class="btn px-4 py-2" style="background-color: var(--primary-color); color: white; border-radius: 8px; font-weight: 500; text-decoration: none;">
                <i class="fa-solid fa-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
