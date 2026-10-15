@extends('layouts.vendor')

@section('title', 'Menu Management')

@section('content')
<style>
    /* Edit Modal */
    .edit-menu-modal {
        display: none;
        position: fixed;
        inset: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;

        align-items: center;
        justify-content: center;

        padding: 20px;
        overflow-y: auto;
        box-sizing: border-box;
    }

    .edit-menu-modal-content {
        background: #fff;
        width: 100%;
        max-width: 520px;

        max-height: calc(100vh - 40px);
        overflow-y: auto;

        padding: 24px;
        border-radius: 12px;
        box-sizing: border-box;

        margin: auto;
    }

    /* Modal scrollbar */
    .edit-menu-modal-content::-webkit-scrollbar {
        width: 7px;
    }

    .edit-menu-modal-content::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .edit-menu-modal-content::-webkit-scrollbar-thumb {
        background: #94a3b8;
        border-radius: 10px;
    }

    /* Mobile */
    @media (max-width: 600px) {

        .edit-menu-modal {
            padding: 10px;
            align-items: flex-start;
        }

        .edit-menu-modal-content {
            max-width: 100%;
            max-height: calc(100vh - 20px);
            padding: 18px;
            margin-top: 5px;
            border-radius: 10px;
        }

        .edit-menu-modal-content h3 {
            font-size: 18px;
        }

        .edit-menu-modal-content input,
        .edit-menu-modal-content select,
        .edit-menu-modal-content textarea {
            font-size: 14px;
        }
    }






    .menu-layout-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 24px;
    }

    @media (max-width: 992px) {
        .menu-layout-grid {
            grid-template-columns: 1fr;
        }
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table-responsive table {
        width: 100%;
        min-width: 500px;
    }

    @media (max-width: 768px) {
        .table-responsive table {
            min-width: 100%;
        }

        th,
        td {
            padding: 10px 8px !important;
            font-size: 13px !important;
        }

        .btn-primary,
        .btn-danger {
            padding: 4px 8px !important;
            font-size: 12px !important;
        }

        .badge {
            font-size: 11px !important;
            padding: 4px 6px !important;
        }
    }

    /* Custom scrollbar to make it always visible */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #94a3b8;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }
</style>

<div class="menu-layout-grid">

    <!-- Left Column: Menu List -->
    <div>
        @if ($errors->any())
        <div style="background: #fee2e2; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card">
            <h3 style="margin-bottom: 20px; color: #0f172a;">Your Menu Items</h3>
            @if($menus->count() > 0)
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Food Type</th>
                            <th>Price</th>
                            <th>Timing</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $menu)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    @if($menu->image)
                                    <img src="{{ $menu->image ? (Str::startsWith($menu->image, 'http') ? $menu->image : asset('storage/'.$menu->image)) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=100&q=80' }}" alt="image" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                                    @else
                                    <div style="width: 40px; height: 40px; border-radius: 8px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; color: #94a3b8;"><i class="fa-solid fa-image"></i></div>
                                    @endif
                                    <div>
                                        <div style="font-weight: 700; color: #1e293b;">{{ $menu->item_name }}</div>
                                        <div style="font-size: 12px; color: #64748b;">{{ $menu->category ?? 'General' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($menu->food_type === 'veg')
                                <span style="
            color: #16a34a;
            background: #dcfce7;
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
        ">
                                    🟢 Veg
                                </span>
                                @else
                                <span style="
            color: #dc2626;
            background: #fee2e2;
            padding: 4px 8px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
        ">
                                    🔴 Non-Veg
                                </span>
                                @endif
                            </td>

                            <td style="font-weight: 600;">₹{{ number_format($menu->price, 2) }}</td>
                            <td><span style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 12px;">{{ ucfirst($menu->timing_type) }}</span></td>
                            <td>
                                <form action="{{ route('vendor.menus.toggle', $menu->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; cursor: pointer;">
                                        @if($menu->availability)
                                        <span class="badge badge-delivered"><i class="fa-solid fa-check-circle"></i> Available</span>
                                        @else
                                        <span class="badge badge-preparing"><i class="fa-solid fa-times-circle"></i> Unavailable</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <button type="button" class="btn btn-primary" style="padding: 6px 10px;" onclick='openEditModal(@json($menu))'><i class="fa-solid fa-pen"></i></button>
                                    <form action="{{ route('vendor.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Delete this item?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="padding: 6px 10px;"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div style="text-align: center; padding: 40px 0; color: #64748b;">
                <i class="fa-solid fa-burger" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                <p>No menu items added yet.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Right Column: Add Item Form -->
    <div>
        <div class="card" style="position: sticky; top: 20px;">
            <h3 style="margin-bottom: 20px; color: #0f172a;">Add New Item</h3>
            <form action="{{ route('vendor.menus.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">Item Name</label>
                    <input type="text" name="item_name" required placeholder="e.g., Chicken Biryani">
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">Price (₹)</label>
                    <input type="number" step="0.01" name="price" required placeholder="150.00">
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">Category</label>
                    <input type="text" name="category" placeholder="e.g., Main Course, Starters">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">
                        Food Type
                    </label>

                    <div style="display: flex; gap: 20px; align-items: center;">

                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                            <input type="radio"
                                name="food_type"
                                value="veg"
                                checked>
                            <span style="color: #16a34a; font-weight: 600;">
                                🟢 Veg
                            </span>
                        </label>

                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                            <input type="radio"
                                name="food_type"
                                value="non_veg">
                            <span style="color: #dc2626; font-weight: 600;">
                                🔴 Non-Veg
                            </span>
                        </label>

                    </div>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">Description (Optional)</label>
                    <textarea name="description" placeholder="e.g., Delicious gongura chicken prepared fresh" style="width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px; outline: none; resize: vertical; min-height: 60px; font-family: inherit; font-size: 14px;"></textarea>
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">Rating (Optional)</label>
                    <input type="number" step="0.1" min="0" max="5" name="rating" placeholder="e.g., 4.8" style="width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px; outline: none;">
                </div>
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">Timing</label>
                    <select name="timing_type" required>
                        <option value="all">All Day</option>
                        <option value="breakfast">Breakfast</option>
                        <option value="lunch">Lunch</option>
                        <option value="dinner">Dinner</option>
                        <option value="snacks">Snacks</option>
                    </select>
                </div>
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">Image</label>
                    <input type="file" name="image" accept="image/*" style="padding: 6px;">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Add Menu Item</button>
            </form>
        </div>
    </div>
</div>
<div id="editMenuModal" class="edit-menu-modal">
    <div class="edit-menu-modal-content">
        <h3 style="margin-bottom: 20px; color: #0f172a;">Edit Menu Item</h3>
        <form id="editMenuForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">Item Name</label>
                <input type="text" name="item_name" id="edit_item_name" required style="width:100%; padding:8px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">Price (₹)</label>
                <input type="number" step="0.01" name="price" id="edit_price" required style="width:100%; padding:8px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">Category</label>
                <input type="text" name="category" id="edit_category" style="width:100%; padding:8px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">
                    Food Type
                </label>

                <div style="display: flex; gap: 20px; align-items: center;">

                    <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                        <input type="radio"
                            name="food_type"
                            id="edit_food_type_veg"
                            value="veg">
                        <span style="color: #16a34a; font-weight: 600;">
                            🟢 Veg
                        </span>
                    </label>

                    <label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
                        <input type="radio"
                            name="food_type"
                            id="edit_food_type_non_veg"
                            value="non_veg">
                        <span style="color: #dc2626; font-weight: 600;">
                            🔴 Non-Veg
                        </span>
                    </label>

                </div>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">Description (Optional)</label>
                <textarea name="description" id="edit_description" style="width: 100%; padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px; outline: none; resize: vertical; min-height: 60px; font-family: inherit; font-size: 14px;"></textarea>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">Rating (Optional)</label>
                <input type="number" step="0.1" min="0" max="5" name="rating" id="edit_rating" style="width:100%; padding:8px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">Timing</label>
                <select name="timing_type" id="edit_timing" required style="width:100%; padding:8px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
                    <option value="all">All Day</option>
                    <option value="breakfast">Breakfast</option>
                    <option value="lunch">Lunch</option>
                    <option value="dinner">Dinner</option>
                    <option value="snacks">Snacks</option>
                </select>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px;">Image (Leave empty to keep current)</label>
                <input type="file" name="image" accept="image/*" style="width:100%; padding:6px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
            </div>
            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn btn-primary" style="flex:1; justify-content:center;">Save Changes</button>
                <button type="button" class="btn" style="flex:1; justify-content:center; background:#f1f5f9; color:#475569;" onclick="document.getElementById('editMenuModal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(menu) {

        document.getElementById('edit_item_name').value = menu.item_name;
        document.getElementById('edit_price').value = menu.price;
        document.getElementById('edit_category').value = menu.category || '';
        document.getElementById('edit_description').value = menu.description || '';
        document.getElementById('edit_rating').value = menu.rating || '';
        document.getElementById('edit_timing').value = menu.timing_type || 'all';
        if (menu.food_type === 'non_veg') {
            document.getElementById('edit_food_type_non_veg').checked = true;
        } else {
            document.getElementById('edit_food_type_veg').checked = true;
        }
        document.getElementById('editMenuForm').action = "{{ url('vendor/menus') }}/" + menu.id;
        document.getElementById('editMenuModal').style.display = 'flex';
    }
</script>
@endsection
