<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Restaurant;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'restaurant', 'order'])->orderBy('created_at', 'desc');

        // Stats calculation
        $totalReviews = Review::count();
        $avgRating = Review::avg('rating') ?? 0;
        $fiveStarCount = Review::where('rating', 5)->count();
        $fourStarCount = Review::where('rating', 4)->count();
        $threeAndBelowCount = Review::where('rating', '<=', 3)->count();
        
        $stats = [
            'total' => $totalReviews,
            'average' => number_format($avgRating, 1),
            'five_star' => $fiveStarCount,
            'four_star' => $fourStarCount,
            'three_below' => $threeAndBelowCount,
        ];

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('restaurant', function($q) use ($search) {
                $q->where('restaurant_name', 'like', "%{$search}%");
            });
        }

        // Restaurant Filter
        if ($request->filled('restaurant_id') && $request->restaurant_id != 'all') {
            $query->where('restaurant_id', $request->restaurant_id);
        }

        // Rating Filter
        if ($request->filled('rating') && $request->rating != 'all') {
            $query->where('rating', $request->rating);
        }

        // Date Range Filter
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('created_at', [
                $request->from_date . ' 00:00:00',
                $request->to_date . ' 23:59:59'
            ]);
        }

        // Export to CSV
        if ($request->has('export')) {
            $exportReviews = $query->get();
            $filename = "reviews_" . date('Y-m-d_H-i') . ".csv";
            
            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );

            $columns = array('Review ID', 'Customer', 'Restaurant', 'Order ID', 'Rating', 'Review', 'Date');

            $callback = function() use($exportReviews, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($exportReviews as $review) {
                    fputcsv($file, array(
                        $review->id,
                        $review->user->name ?? 'N/A',
                        $review->restaurant->restaurant_name ?? 'N/A',
                        '#ORD' . str_pad($review->order_id, 4, '0', STR_PAD_LEFT),
                        $review->rating,
                        $review->review_text,
                        $review->created_at->format('M d, Y')
                    ));
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        $reviews = $query->paginate(15);
        $reviews->appends($request->all());
        
        $restaurants = Restaurant::select('id', 'restaurant_name')->get();

        return view('admin.reviews.index', compact('reviews', 'stats', 'restaurants'));
    }

    public function show($id)
    {
        $review = Review::with(['user', 'restaurant', 'order'])->findOrFail($id);
        return view('admin.reviews.show', compact('review'));
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return back()->with('success', 'Review deleted successfully!');
    }
}
