@extends('layouts.admin')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    .breadcrumb {
        font-size: 0.875rem;
        color: #64748b;
        margin-top: 4px;
    }
    .breadcrumb a {
        color: #64748b;
        text-decoration: none;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .stat-icon.emerald { background: #ecfdf5; color: #10b981; }
    .stat-icon.blue { background: #eff6ff; color: #3b82f6; }
    .stat-icon.orange { background: #fff7ed; color: #f97316; }
    .stat-icon.indigo { background: #e0e7ff; color: #6366f1; }
    
    .stat-info { display: flex; align-items: center; justify-content: space-between; flex: 1; }
    .stat-info p {
        margin: 0;
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
    }
    .stat-info h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
    }

    /* Filter Bar */
    .filter-bar {
        display: flex;
        gap: 12px;
        align-items: center;
        margin-bottom: 24px;
    }
    .filter-input {
        flex: 2;
        position: relative;
    }
    .filter-input i {
        position: absolute;
        left: 12px;
        top: 12px;
        color: #94a3b8;
    }
    .filter-input input {
        width: 100%;
        padding: 10px 12px 10px 36px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #1e293b;
        outline: none;
        background: #fff;
    }
    .filter-select {
        flex: 1;
    }
    .filter-select select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #1e293b;
        outline: none;
        background: #fff;
    }
    .btn-filter-solid {
        background: #6366f1;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-reset {
        background: white;
        color: #64748b;
        border: 1px solid #e2e8f0;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Tabs */
    .nav-tabs {
        display: flex;
        gap: 24px;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 24px;
    }
    .nav-tab {
        padding: 12px 0;
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        text-decoration: none;
        position: relative;
    }
    .nav-tab.active {
        color: #6366f1;
    }
    .nav-tab.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 2px;
        background: #6366f1;
    }

    /* Table Styles */
    .users-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .table {
        margin: 0;
        width: 100%;
        border-collapse: collapse;
    }
    .table th {
        background: #f8fafc;
        padding: 16px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .table td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
        font-size: 0.875rem;
    }
    .table tr:last-child td {
        border-bottom: none;
    }

    /* Badges */
    .status-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-active { background: #ecfdf5; color: #10b981; }
    .status-inactive { background: #fef2f2; color: #ef4444; }

    .btn-icon {
        background: #f1f5f9;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .btn-icon:hover {
        background: #e2e8f0;
        color: #6366f1;
    }
    .btn-icon.delete:hover {
        color: #ef4444;
    }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Users & Roles</h1>
        <div class="breadcrumb">
            <a href="#">Home</a> <i class="fa-solid fa-chevron-right" style="font-size:0.7rem; margin:0 8px;"></i> Users & Roles
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon emerald"><i class="fa-solid fa-users"></i></div>
        <div class="stat-info">
            <p>Total Users</p>
            <h3>48</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-user-check"></i></div>
        <div class="stat-info">
            <p>Active Users</p>
            <h3>42</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-user-minus"></i></div>
        <div class="stat-info">
            <p>Inactive Users</p>
            <h3>6</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon indigo"><i class="fa-solid fa-user-shield"></i></div>
        <div class="stat-info">
            <p>Roles</p>
            <h3>6</h3>
        </div>
    </div>
</div>

<div class="filter-bar">
    <div class="filter-input">
        <i class="fa-solid fa-search"></i>
        <input type="text" placeholder="Search by name, email or phone...">
    </div>
    <div class="filter-select">
        <select>
            <option>All Roles</option>
            <option>Admin</option>
            <option>Manager</option>
            <option>Support</option>
        </select>
    </div>
    <div class="filter-select">
        <select>
            <option>All Status</option>
            <option>Active</option>
            <option>Inactive</option>
        </select>
    </div>
    <button class="btn-filter-solid"><i class="fa-solid fa-filter"></i> Filter</button>
    <button class="btn-reset">↻ Reset</button>
</div>

<div class="nav-tabs">
    <a href="#" class="nav-tab active">Users</a>
    <a href="#" class="nav-tab">Roles</a>
</div>

<div class="users-card">
    <table class="table">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email / Phone</th>
                <th>Role</th>
                <th>Status</th>
                <th>Joined On</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>USR1001</strong></td>
                <td style="font-weight: 600; color: #1e293b;">Ravi Kumar</td>
                <td>
                    <div style="color: #64748b; margin-bottom:4px;">ravi.kumar@email.com</div>
                    <div style="font-size: 0.75rem;">9876543210</div>
                </td>
                <td>Admin</td>
                <td><span class="status-badge status-active">Active</span></td>
                <td>Jul 28, 2026</td>
                <td>
                    <button class="btn-icon"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn-icon delete"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td><strong>USR1002</strong></td>
                <td style="font-weight: 600; color: #1e293b;">Suresh Babu</td>
                <td>
                    <div style="color: #64748b; margin-bottom:4px;">suresh@email.com</div>
                    <div style="font-size: 0.75rem;">9876543211</div>
                </td>
                <td>Manager</td>
                <td><span class="status-badge status-active">Active</span></td>
                <td>Jul 29, 2026</td>
                <td>
                    <button class="btn-icon"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn-icon delete"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td><strong>USR1003</strong></td>
                <td style="font-weight: 600; color: #1e293b;">Anil Reddy</td>
                <td>
                    <div style="color: #64748b; margin-bottom:4px;">anil.reddy@email.com</div>
                    <div style="font-size: 0.75rem;">9876543212</div>
                </td>
                <td>Support</td>
                <td><span class="status-badge status-active">Active</span></td>
                <td>Jul 30, 2026</td>
                <td>
                    <button class="btn-icon"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn-icon delete"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td><strong>USR1004</strong></td>
                <td style="font-weight: 600; color: #1e293b;">Deepak Singh</td>
                <td>
                    <div style="color: #64748b; margin-bottom:4px;">deepak@email.com</div>
                    <div style="font-size: 0.75rem;">9876543213</div>
                </td>
                <td>Finance</td>
                <td><span class="status-badge status-active">Active</span></td>
                <td>Jul 30, 2026</td>
                <td>
                    <button class="btn-icon"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn-icon delete"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
            <tr>
                <td><strong>USR1005</strong></td>
                <td style="font-weight: 600; color: #1e293b;">Karthik</td>
                <td>
                    <div style="color: #64748b; margin-bottom:4px;">karthik@email.com</div>
                    <div style="font-size: 0.75rem;">9876543214</div>
                </td>
                <td>Operator</td>
                <td><span class="status-badge status-inactive">Inactive</span></td>
                <td>Jul 31, 2026</td>
                <td>
                    <button class="btn-icon"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn-icon delete"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div style="padding: 16px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
        <span style="font-size: 0.875rem; color: #64748b;">Showing 1 to 5 of 48 users</span>
        <div style="display: flex; gap: 4px;">
            <button class="btn-icon" style="background: white; border: 1px solid #e2e8f0; width: 32px; height: 32px;"><i class="fa-solid fa-chevron-left" style="font-size:0.75rem;"></i></button>
            <button class="btn-icon" style="background: #6366f1; color: white; width: 32px; height: 32px; font-size:0.875rem; font-weight:600; border-radius:6px;">1</button>
            <button class="btn-icon" style="background: white; border: 1px solid #e2e8f0; width: 32px; height: 32px; font-size:0.875rem; font-weight:600; border-radius:6px;">2</button>
            <button class="btn-icon" style="background: white; border: 1px solid #e2e8f0; width: 32px; height: 32px; font-size:0.875rem; font-weight:600; border-radius:6px;">3</button>
            <span style="display:inline-flex; align-items:center; padding:0 8px; color:#94a3b8;">...</span>
            <button class="btn-icon" style="background: white; border: 1px solid #e2e8f0; width: 32px; height: 32px; font-size:0.875rem; font-weight:600; border-radius:6px;">10</button>
            <button class="btn-icon" style="background: white; border: 1px solid #e2e8f0; width: 32px; height: 32px;"><i class="fa-solid fa-chevron-right" style="font-size:0.75rem;"></i></button>
        </div>
    </div>
</div>

@endsection
