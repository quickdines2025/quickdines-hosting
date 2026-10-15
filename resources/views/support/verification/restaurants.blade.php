@extends('layouts.support')

@section('title', 'Restaurant Verification')

@section('content')
<style>
    .page-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;
    }
    .card {
        background: white; border-radius: 12px; border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); overflow: hidden;
    }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 16px 20px; text-align: left; border-bottom: 1px solid #e2e8f0; }
    .table th { background: #f8fafc; font-weight: 600; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
    .table td { font-size: 14px; color: #1e293b; vertical-align: middle; }
    .table tbody tr:hover { background: #f8fafc; }
    
    .empty-state { padding: 60px 20px; text-align: center; color: #64748b; }
    .empty-state i { font-size: 48px; margin-bottom: 16px; opacity: 0.5; }
</style>

<div class="page-header">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0;">Restaurant Verification</h1>
        <p style="color: #64748b; margin-top: 4px; font-size: 0.875rem;">Review and verify new restaurant registrations</p>
    </div>
</div>

<div class="card">
    @if($pendingRestaurants->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Restaurant Details</th>
                    <th>Contact Info</th>
                    <th>Documents</th>
                    <th>Submitted On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingRestaurants as $restaurant)
                <tr>
                    <td>
                        <div style="font-weight: 600; color: #0f172a;">{{ $restaurant->restaurant_name }}</div>
                        <div style="font-size: 12px; color: #64748b;">{{ $restaurant->cuisines ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 500;">{{ $restaurant->owner_name }}</div>
                        <div style="font-size: 12px; color: #64748b;">{{ $restaurant->mobile }}</div>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            @if($restaurant->document_path)
                                <a href="#" onclick="openDocModal('{{ asset('storage/'.$restaurant->document_path) }}'); return false;" class="btn" style="background: #f1f5f9; border: 1px solid #cbd5e1; padding: 4px 8px; border-radius: 4px; font-size: 12px; color: #3b82f6; text-decoration: none;"><i class="fa-solid fa-file-pdf"></i> Doc 1</a>
                            @endif
                            @if($restaurant->image)
                                <a href="#" onclick="openDocModal('{{ asset('storage/'.$restaurant->image) }}'); return false;" class="btn" style="background: #f1f5f9; border: 1px solid #cbd5e1; padding: 4px 8px; border-radius: 4px; font-size: 12px; color: #3b82f6; text-decoration: none;"><i class="fa-solid fa-image"></i> Doc 2</a>
                            @endif
                            @if(!$restaurant->document_path && !$restaurant->image)
                                <span style="font-size: 12px; color: #94a3b8; font-style: italic;">No document</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 500;">{{ $restaurant->created_at->format('M d, Y') }}</div>
                        <div style="font-size: 12px; color: #64748b;">{{ $restaurant->created_at->format('h:i A') }}</div>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <form action="{{ route('support.verification.restaurant.approve', $restaurant->id) }}" method="POST" onsubmit="return confirm('Approve this restaurant?');">
                                @csrf
                                <button type="submit" class="btn" style="background: #16a34a; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; color: white; font-weight: 500; font-size: 13px;"><i class="fa-solid fa-check"></i> Approve</button>
                            </form>
                            <form action="{{ route('support.verification.restaurant.reject', $restaurant->id) }}" method="POST" onsubmit="return confirm('Reject this restaurant?');">
                                @csrf
                                <button type="submit" class="btn" style="background: white; border: 1px solid #fca5a5; padding: 6px 12px; border-radius: 6px; cursor: pointer; color: #dc2626; font-weight: 500; font-size: 13px;"><i class="fa-solid fa-xmark"></i> Reject</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <i class="fa-regular fa-circle-check"></i>
            <h3 style="margin-bottom: 8px; font-size: 1.1rem; color: #1e293b;">No Pending Restaurants</h3>
            <p style="font-size: 0.9rem;">There are currently no new restaurant registrations awaiting verification.</p>
        </div>
    @endif
</div>

<!-- Document Modal -->
<div id="documentModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; justify-content: center; align-items: center;">
    <div style="width: 80%; height: 85%; position: relative; background: white; border-radius: 12px; padding: 30px 20px 20px 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <button onclick="closeDocModal()" style="position: absolute; top: 12px; right: 15px; border: none; background: #fef2f2; width: 32px; height: 32px; border-radius: 50%; font-size: 20px; cursor: pointer; color: #ef4444; display: flex; justify-content: center; align-items: center; transition: 0.2s;" onmouseover="this.style.background='#fca5a5'" onmouseout="this.style.background='#fef2f2'">&times;</button>
        <div style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; overflow: hidden; background: #f8fafc; border-radius: 8px;">
            <iframe id="docIframe" src="" style="width: 100%; height: 100%; border: none; display: none;"></iframe>
            <img id="docImg" src="" style="max-width: 100%; max-height: 100%; object-fit: contain; display: none;" alt="Document Preview">
        </div>
    </div>
</div>

<script>
    function openDocModal(url) {
        let isImage = url.match(/\.(jpeg|jpg|gif|png|webp|svg|bmp)(?:\?.*)?$/i) != null;
        if(isImage) {
            document.getElementById('docIframe').style.display = 'none';
            document.getElementById('docImg').style.display = 'block';
            document.getElementById('docImg').src = url;
        } else {
            document.getElementById('docImg').style.display = 'none';
            document.getElementById('docIframe').style.display = 'block';
            document.getElementById('docIframe').src = url;
        }
        document.getElementById('documentModal').style.display = 'flex';
    }
    
    function closeDocModal() {
        document.getElementById('documentModal').style.display = 'none';
        document.getElementById('docIframe').src = '';
        document.getElementById('docImg').src = '';
    }
</script>

@endsection
