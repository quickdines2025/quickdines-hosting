@extends('layouts.vendor')

@section('title', 'Analytics & Reports')

@push('styles')
<style>
    /* Page Header */
    .page-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }

    .page-subtitle {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Analytics Cards */
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        border: 1px solid #e2e8f0;
        height: 100%;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 16px;
    }

    .icon-revenue {
        background: #dcfce7;
        color: #16a34a;
    }

    .icon-orders {
        background: #e0e7ff;
        color: #4f46e5;
    }

    .icon-aov {
        background: #ffedd5;
        color: #1d4ed8;
    }

    .icon-views {
        background: #f3e8ff;
        color: #9333ea;
    }

    .stat-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 1.875rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .stat-trend {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .trend-up {
        color: #10b981;
        background: #dcfce7;
        padding: 2px 8px;
        border-radius: 12px;
    }

    .trend-down {
        color: #ef4444;
        background: #fef2f2;
        padding: 2px 8px;
        border-radius: 12px;
    }

    .trend-text {
        color: #94a3b8;
        font-weight: 500;
    }

    /* Chart & Top Items Sections */
    .section-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        border: 1px solid #e2e8f0;
        height: 100%;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .section-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    /* Top Items List */
    .top-item {
        display: flex;
        align-items: center;
        padding: 16px;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        margin-bottom: 12px;
        background: #f8fafc;
        transition: background 0.2s;
    }

    .top-item:hover {
        background: white;
        border-color: #e2e8f0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .item-rank {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #2563eb;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.9rem;
        margin-right: 16px;
    }

    .item-info {
        flex: 1;
    }

    .item-name {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.95rem;
        margin-bottom: 2px;
    }

    .item-sales {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
    }

    .item-revenue {
        font-weight: 800;
        color: #0f172a;
    }

    /* Chart Container */
    .chart-container {
        width: 100%;
        height: 300px;
        position: relative;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }

    @media(max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .stat-card {
            padding: 16px;
        }

        .stat-icon {
            width: 36px;
            height: 36px;
            margin-bottom: 12px;
            font-size: 1rem;
        }

        .stat-value {
            font-size: 1.25rem;
        }

        .stat-label {
            font-size: 0.75rem;
        }

        .stat-trend {
            font-size: 0.7rem;
        }
    }

    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }

    @media(max-width: 992px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .align-items-center {
        align-items: center;
    }

    .flex-wrap {
        flex-wrap: wrap;
    }

    .gap-3 {
        gap: 16px;
    }

    .gap-2 {
        gap: 8px;
    }

    .mb-4 {
        margin-bottom: 24px;
    }

    .mb-2 {
        margin-bottom: 8px;
    }

    .btn {
        cursor: pointer;
        padding: 8px 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>
<!-- Add Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 class="page-title">Analytics</h2>
        <div class="page-subtitle">Track your business performance and growth</div>
    </div>

    <div class="d-flex gap-2">
        <select class="form-select border-0 shadow-sm" style="border-radius: 8px; font-weight: 500; color: #475569; background-color: white;">
            <option>Last 7 Days</option>
            <option>This Month</option>
            <option>All Time</option>
        </select>
        <a href="{{ route('vendor.analytics.download', [
    'start_date' => $startDate ?? \Carbon\Carbon::now()->subDays(6)->format('Y-m-d'),
    'end_date' => $endDate ?? \Carbon\Carbon::now()->format('Y-m-d')
]) }}"
            class="btn"
            style="
       background: white;
       border: none;
       border-radius: 8px;
       box-shadow: 0 1px 2px rgba(0,0,0,0.05);
       color: #64748b;
       text-decoration: none;
   "
            title="Download Report">
            <i class="fa-solid fa-download"></i>
        </a>
    </div>
</div>

<div class="stats-grid">
    <!-- Total Revenue -->
    <div>
        <div class="stat-card">
            <div class="stat-icon icon-revenue">
                <i class="fa-solid fa-indian-rupee-sign"></i>
            </div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">₹{{ number_format($totalRevenue, 0) }}</div>
            <div class="stat-trend">
                <span class="trend-up"><i class="fa-solid fa-arrow-trend-up"></i> 12.5%</span>
                <span class="trend-text">vs last week</span>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div>
        <div class="stat-card">
            <div class="stat-icon icon-orders">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
            <div class="stat-label">Total Orders</div>
            <div class="stat-value">{{ number_format($totalOrders) }}</div>
            <div class="stat-trend">
                <span class="trend-up"><i class="fa-solid fa-arrow-trend-up"></i> 8.2%</span>
                <span class="trend-text">vs last week</span>
            </div>
        </div>
    </div>

    <!-- Average Order Value -->
    <div>
        <div class="stat-card">
            <div class="stat-icon icon-aov">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
            <div class="stat-label">Avg. Order Value</div>
            <div class="stat-value">₹{{ number_format($avgOrderValue, 0) }}</div>
            <div class="stat-trend">
                <span class="trend-down"><i class="fa-solid fa-arrow-trend-down"></i> 2.1%</span>
                <span class="trend-text">vs last week</span>
            </div>
        </div>
    </div>

    <!-- Menu Views (Placeholder stat) -->
    <div>
        <div class="stat-card">
            <div class="stat-icon icon-views">
                <i class="fa-regular fa-eye"></i>
            </div>
            <div class="stat-label">Menu Views</div>
            <div class="stat-value">{{ number_format($totalViews) }}</div>
            <div class="stat-trend">
                <span class="trend-up"><i class="fa-solid fa-arrow-trend-up"></i> 24.5%</span>
                <span class="trend-text">vs last week</span>
            </div>
        </div>
    </div>
</div>

<div class="content-grid">
    <!-- Revenue Chart Area -->
    <div>
        <div class="section-card">
            <div class="section-header" style="flex-wrap: wrap; gap: 10px;">
                <h3 class="section-title">Revenue Trend</h3>
                <form action="{{ route('vendor.analytics') }}" method="GET" style="display: flex; gap: 8px; align-items: center;">
                    <input type="date" name="start_date" value="{{ $startDate ?? \Carbon\Carbon::now()->subDays(6)->format('Y-m-d') }}" style="padding: 4px 8px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 12px; color: #475569;">
                    <span style="color: #64748b; font-size: 12px;">to</span>
                    <input type="date" name="end_date" value="{{ $endDate ?? \Carbon\Carbon::now()->format('Y-m-d') }}" style="padding: 4px 8px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 12px; color: #475569;">
                    <button type="submit" class="btn btn-sm" style="background: var(--primary); color: white; padding: 4px 12px; border-radius: 6px; border: none; font-size: 12px; cursor: pointer;">Filter</button>
                </form>
            </div>

            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Selling Items -->
    <div>
        <div class="section-card">
            <div class="section-header">
                <h3 class="section-title">Top Selling Items</h3>
                <a href="{{ route('vendor.menus.index') }}" style="font-size: 0.85rem; font-weight: 600; color: #2563eb; text-decoration: none;">View Menu</a>
            </div>

            <div class="top-items-list">
                @forelse($topItems as $index => $item)
                <div class="top-item">
                    <div class="item-rank">{{ $index + 1 }}</div>
                    <div class="item-info">
                        <div class="item-name">{{ $item->name }}</div>
                        <div class="item-sales">{{ $item->total_sold }} units sold</div>
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 40px 0; color: #94a3b8;">
                    <i class="fa-solid fa-utensils mb-2" style="font-size: 24px;"></i>
                    <div style="font-weight: 500; font-size: 0.9rem;">Not enough data yet</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');

        // Data from controller
        const chartData = @json($chartData);

        // Gradient for line
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.5)');
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Revenue (₹)',
                    data: chartData.data,
                    borderColor: '#2563eb',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4 // smooth curves
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: {
                            size: 13,
                            family: 'Inter'
                        },
                        bodyFont: {
                            size: 14,
                            family: 'Inter',
                            weight: 'bold'
                        },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '₹' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [4, 4],
                            color: '#e2e8f0',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                family: 'Inter',
                                size: 12
                            },
                            color: '#64748b',
                            callback: function(value) {
                                return '₹' + value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                family: 'Inter',
                                size: 12
                            },
                            color: '#64748b'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    });
</script>
@endpush
