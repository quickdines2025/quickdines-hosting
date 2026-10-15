@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0" style="font-weight: 700; color: var(--text-main);">Messages</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary" style="border-radius: 8px;">Back to Dashboard</a>
</div>

<div class="glass-card" style="min-height: 400px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 3rem;">
    <div style="background: rgba(16, 185, 129, 0.1); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
        <i class="fa-regular fa-message" style="font-size: 2.5rem; color: #10b981;"></i>
    </div>
    <h3 style="font-weight: 600; color: var(--text-main); margin-bottom: 0.5rem;">No Messages</h3>
    <p style="color: var(--text-muted); max-width: 400px;">Your inbox is empty. Messages from vendors, drivers, or system administrators will appear here once the messaging feature is fully integrated.</p>
</div>
@endsection
