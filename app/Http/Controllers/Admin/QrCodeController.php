<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bus;

class QrCodeController extends Controller
{
    public function index(Request $request)
    {
        $query = Bus::query();

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bus_number', 'like', "%{$search}%")
                  ->orWhere('bus_name', 'like', "%{$search}%")
                  ->orWhere('operator', 'like', "%{$search}%")
                  ->orWhere('qr_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('route')) {
            // we will search route_from or route_to
            $query->where(function($q) use ($request) {
                $q->where('route_from', $request->route)
                  ->orWhere('route_to', $request->route);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('operator')) {
            $query->where('operator', $request->operator);
        }

        $buses = $query->paginate(10)->withQueryString();
        
        $totalQRCodes = Bus::count();
        $activeQRCodes = Bus::where('status', 1)->count();
        $inactiveQRCodes = Bus::where('status', 0)->count();
        $totalScans = Bus::sum('total_scans') ?? 0;
        $avgScans = $totalQRCodes > 0 ? round($totalScans / $totalQRCodes, 2) : 0;

        // Fetch distinct routes for filter
        $routesFrom = Bus::select('route_from')->whereNotNull('route_from')->distinct()->pluck('route_from');
        $routesTo = Bus::select('route_to')->whereNotNull('route_to')->distinct()->pluck('route_to');
        $routes = $routesFrom->merge($routesTo)->unique()->filter()->values();
        
        // Fetch distinct operators for filter
        $operators = Bus::select('operator')->whereNotNull('operator')->distinct()->pluck('operator');

        return view('admin.qr.index', compact(
            'buses',
            'totalQRCodes',
            'activeQRCodes',
            'inactiveQRCodes',
            'totalScans',
            'avgScans',
            'routes',
            'operators'
        ));
    }

    public function export(Request $request)
    {
        $buses = Bus::all();
        $fileName = 'qr_codes_report_' . date('Y-m-d') . '.csv';
        
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        
        $columns = array('Bus Number', 'Bus Name', 'Operator', 'Route', 'QR Code ID', 'Status', 'Total Scans', 'Last Scanned At', 'Created At');
        
        $callback = function() use($buses, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($buses as $bus) {
                $status = $bus->status ? 'Active' : 'Inactive';
                $route = ($bus->route_from && $bus->route_to) ? $bus->route_from . ' -> ' . $bus->route_to : 'N/A';
                
                fputcsv($file, array(
                    $bus->bus_number,
                    $bus->bus_name ?? '-',
                    $bus->operator ?? '-',
                    $route,
                    $bus->qr_code ?? '-',
                    $status,
                    $bus->total_scans,
                    $bus->last_scanned_at ?? 'Never',
                    $bus->created_at->format('Y-m-d H:i:s')
                ));
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function regenerate(Request $request, $id)
    {
        $bus = Bus::findOrFail($id);
        $bus->qr_code = 'QD-' . strtoupper(substr(uniqid(), -8));
        $bus->save();

        return back()->with('success', 'QR Code regenerated successfully.');
    }

    public function toggleStatus(Request $request, $id)
    {
        $bus = Bus::findOrFail($id);
        $bus->status = $bus->status ? 0 : 1;
        $bus->save();

        $statusText = $bus->status ? 'activated' : 'disabled';
        return back()->with('success', "QR Code $statusText successfully.");
    }}
