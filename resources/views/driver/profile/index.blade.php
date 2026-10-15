@extends('layouts.driver')

@section('bus_number', $driver->bus->bus_number ?? 'Not Assigned')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header-title">
        <h3>Driver Profile</h3>
    </div>
    
    <form action="{{ route('driver.profile.update') }}" method="POST">
        @csrf
        <div style="text-align: center; margin-bottom: 30px;">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=1e293b&color=fff&size=100" style="border-radius: 50%; margin-bottom: 16px;">
            <div style="margin-bottom: 10px;">
                <input type="text" name="name" value="{{ Auth::user()->name }}" class="form-control" style="text-align: center; font-size: 1.5rem; font-weight: 700; border: 1px solid #e2e8f0; border-radius: 8px; width: 100%; max-width: 300px; margin: 0 auto;">
            </div>
            <p style="color: var(--text-muted);">{{ Auth::user()->email }}</p>
        </div>

        <div style="display: grid; gap: 16px;">
            <div style="background: #f8fafc; padding: 16px; border-radius: 8px;">
                <label style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px; display: block;">Phone Number</label>
                <input type="text" name="mobile" value="{{ Auth::user()->mobile }}" class="form-control" style="font-size: 15px; border: 1px solid #e2e8f0; border-radius: 8px; width: 100%; padding: 8px;">
            </div>
            
            <div style="background: #f8fafc; padding: 16px; border-radius: 8px;">
                <p style="font-size: 14px; font-weight: 600; margin-bottom: 12px; color: #1e293b;">Change Password (Optional)</p>
                <div style="display: grid; gap: 12px;">
                    <div>
                        <label style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px; display: block;">New Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current" style="font-size: 15px; border: 1px solid #e2e8f0; border-radius: 8px; width: 100%; padding: 8px;">
                    </div>
                    <div>
                        <label style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px; display: block;">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password" style="font-size: 15px; border: 1px solid #e2e8f0; border-radius: 8px; width: 100%; padding: 8px;">
                    </div>
                </div>
            </div>

            <div style="background: #f8fafc; padding: 16px; border-radius: 8px;">
                <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Assigned Bus</p>
                <h4 style="font-size: 15px;">{{ $driver->bus->bus_number ?? 'Not Assigned' }}</h4>
            </div>
        <div style="background: #f8fafc; padding: 16px; border-radius: 8px;">
            <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">License Document</p>
            <h4 style="font-size: 15px; color: var(--primary);">
                @if($driver->document_path)
                    <a href="{{ asset('storage/' . $driver->document_path) }}" target="_blank" style="color: var(--primary); text-decoration: none;"><i class="fa-solid fa-file-pdf"></i> View Document</a>
                @else
                    Not Uploaded
                @endif
            </h4>
        </div>
        <div style="text-align: right; margin-top: 10px;">
            <button type="submit" style="background: #3b82f6; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s;">Save Changes</button>
        </div>
    </div>
    </form>
</div>
@endsection
