@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-dark">Edit User: {{ $user->name }}</h6>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back</a>
    </div>
    <div class="card-body">
        
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mobile Number</label>
                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $user->mobile) }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="vendor" {{ old('role', $user->role) == 'vendor' ? 'selected' : '' }}>Vendor</option>
                        <option value="driver" {{ old('role', $user->role) == 'driver' ? 'selected' : '' }}>Driver</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password <small class="text-muted">(Leave blank to keep current password)</small></label>
                    <div class="position-relative">
                        <input type="password" name="password" class="form-control" minlength="8" style="padding-right: 40px;">
                        <i class="fa-regular fa-eye position-absolute" style="top: 50%; right: 12px; transform: translateY(-50%); cursor: pointer; color: #64748b;" onclick="togglePassword(this)"></i>
                    </div>
                </div>
            </div>

            <hr>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Update User</button>
            </div>
        </form>
    </div>
</div>
<script>
    function togglePassword(icon) {
        var input = icon.previousElementSibling;
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
