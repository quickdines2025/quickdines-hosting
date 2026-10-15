<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Restaurant;

class MenuController extends Controller
{
    public function index()
    {
        $restaurant = Restaurant::where('user_id', auth()->id())->first();
        if (!$restaurant) return redirect()->back();

        $menus = Menu::where('restaurant_id', $restaurant->id)->get();
        return view('vendor.menu.index', compact('menus', 'restaurant'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'timing_type' => 'required|string',
            'category' => 'nullable|string',
            'food_type' => 'required|in:veg,non_veg',
            'description' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'image' => 'nullable|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/avif,image/svg+xml,application/octet-stream|max:5120',
        ]);

        $restaurant = auth()->user()->restaurant;

        if (!$restaurant) {
            return redirect()->back()->with('error', 'Please complete your restaurant profile first');
        }

        $menu = new Menu();
        $menu->restaurant_id = $restaurant->id;
        $menu->item_name = $request->item_name;
        $menu->price = $request->price;
        $menu->timing_type = $request->timing_type;
        $menu->category = $request->category;
        $menu->food_type = $request->food_type;
        $menu->description = $request->description;
        $menu->rating = $request->rating;
        $menu->availability = 1;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menus', 'public');
            $menu->image = $path;
        }

        $menu->save();

        return redirect()->back()->with('success', 'Menu item added successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'timing_type' => 'required|string',
            'category' => 'nullable|string',
            'food_type' => 'required|in:veg,non_veg',
            'description' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'image' => 'nullable|mimetypes:image/jpeg,image/png,image/gif,image/webp,image/avif,image/svg+xml,application/octet-stream|max:5120',
        ]);

        $menu = Menu::findOrFail($id);

        $data = $request->except(['_token', '_method', 'image']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menus', 'public');
            $data['image'] = $path;
        }

        $menu->update($data);

        return redirect()->back()->with('success', 'Menu updated successfully');
    }

    public function destroy($id)
    {
        Menu::destroy($id);
        return redirect()->back()->with('success', 'Menu item deleted');
    }

    public function toggleAvailability($id, Request $request)
    {
        $menu = Menu::findOrFail($id);
        $menu->availability = !$menu->availability;
        $menu->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'availability' => $menu->availability]);
        }

        return redirect()->back()->with('success', 'Availability updated');
    }
}
