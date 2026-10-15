<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Menu;

class RestaurantController extends Controller
{
    // public function show($id)
    // {
    //     $restaurant = Restaurant::findOrFail($id);

    //     // Increment views
    //     $restaurant->increment('views');

    //     $menus = Menu::where('restaurant_id', $id)
    //         ->where('availability', 1)
    //         ->get();

    //     // Group menus by category (or just pass all)
    //     // For now, we'll just group by category if it exists, or put in 'All Items'
    //     $groupedMenus = $menus->groupBy(function($menu) {
    //         return $menu->category ?: 'Recommended';
    //     });

    //     $reviews = \App\Models\Review::where('restaurant_id', $restaurant->id)->latest()->get();

    //     return view('frontend.restaurant', compact('restaurant', 'groupedMenus', 'reviews'));
    // }



    // new show
    public function show($id)
    {
        $busId = session('scanned_bus_id');

        // If customer came through QR, check restaurant belongs to that bus
        if ($busId) {
            $bus = \App\Models\Bus::find($busId);

            if (!$bus) {
                session()->forget('scanned_bus_id');
                abort(404, 'Bus not found.');
            }

            $restaurantIds = $bus->restaurants()
                ->where('restaurants.status', 1)
                ->pluck('restaurants.id');

            // Restaurant must belong to scanned bus
            if (!$restaurantIds->contains((int) $id)) {
                abort(403, 'This restaurant is not available for this bus.');
            }
        }

        $restaurant = Restaurant::findOrFail($id);

        // Increment views
        $restaurant->increment('views');

        $menus = Menu::where('restaurant_id', $id)
            ->where('availability', 1)
            ->get();

        $groupedMenus = $menus->groupBy(function ($menu) {
            return $menu->category ?: 'Recommended';
        });

        $reviews = \App\Models\Review::where('restaurant_id', $restaurant->id)
            ->latest()
            ->get();

        return view(
            'frontend.restaurant',
            compact('restaurant', 'groupedMenus', 'reviews')
        );
    }

    // store end





    public function search(Request $request)
    {
        $query = trim($request->input('query', ''));
        $category = trim($request->input('category', ''));

        $menus = collect();

        /*
    |--------------------------------------------------------------------------
    | GET RESTAURANTS ASSIGNED TO SCANNED BUS
    |--------------------------------------------------------------------------
    */

        $busId = session('scanned_bus_id');
        $restaurantIds = null;

        if ($busId) {
            $bus = \App\Models\Bus::find($busId);

            if ($bus) {
                $restaurantIds = $bus->restaurants()
                    ->where('restaurants.status', 1)
                    ->pluck('restaurants.id');
            } else {
                session()->forget('scanned_bus_id');
            }
        }

        /*
    |--------------------------------------------------------------------------
    | CATEGORY CLICK SEARCH
    |--------------------------------------------------------------------------
    */

        if ($category) {

            $categorySearch = strtolower($category);

            /*
        | Search category ONLY inside restaurants assigned to this bus
        */

            $menus = Menu::with('restaurant')
                ->where('availability', 1)
                ->when($restaurantIds !== null, function ($q) use ($restaurantIds) {
                    $q->whereIn('restaurant_id', $restaurantIds);
                })
                ->whereRaw(
                    'LOWER(category) LIKE ?',
                    ['%' . $categorySearch . '%']
                )
                ->get();

            /*
        |--------------------------------------------------------------------------
        | If category is not found,
        | search same text in ITEM NAME
        |--------------------------------------------------------------------------
        */

            if ($menus->isEmpty()) {

                $menus = Menu::with('restaurant')
                    ->where('availability', 1)
                    ->when($restaurantIds !== null, function ($q) use ($restaurantIds) {
                        $q->whereIn('restaurant_id', $restaurantIds);
                    })
                    ->whereRaw(
                        'LOWER(item_name) LIKE ?',
                        ['%' . $categorySearch . '%']
                    )
                    ->get();
            }
        }

        /*
    |--------------------------------------------------------------------------
    | NORMAL SEARCH BOX
    |--------------------------------------------------------------------------
    */ elseif ($query) {

            $search = strtolower($query);

            $menus = Menu::with('restaurant')
                ->where('availability', 1)

                /*
            | IMPORTANT:
            | Only restaurants assigned to scanned bus
            */

                ->when($restaurantIds !== null, function ($q) use ($restaurantIds) {
                    $q->whereIn('restaurant_id', $restaurantIds);
                })

                ->where(function ($q) use ($search) {

                    /*
                | Item name
                */

                    $q->whereRaw(
                        'LOWER(item_name) LIKE ?',
                        ['%' . $search . '%']
                    )

                        /*
                | Category
                */

                        ->orWhereRaw(
                            'LOWER(category) LIKE ?',
                            ['%' . $search . '%']
                        )

                        /*
                | Restaurant name
                */

                        ->orWhereHas('restaurant', function ($restaurantQuery) use ($search) {

                            $restaurantQuery->whereRaw(
                                'LOWER(restaurant_name) LIKE ?',
                                ['%' . $search . '%']
                            );
                        });
                })
                ->get();
        }

        /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

        return view('frontend.search', compact(
            'menus',
            'query',
            'category'
        ));
    }
}
