<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all()->filter(function ($p) {
            if (($p->stock ?? 0) <= 0) {
                return false;
            }
            $imgs = is_array($p->images) ? $p->images : (json_decode($p->images, true) ?: []);
            $first = $imgs[0] ?? ($p->image ?? null);
            if (!$first) return false;
            if (Str::startsWith($first, ['http://', 'https://'])) return true;
            return Storage::disk('public')->exists('products/' . $first);
        })->values();

        return view('products.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $related = Product::where('stock', '>', 0)
            ->where('id', '!=', $id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('products.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        $request->validate([
            'name' => 'required',
            'price_usd' => 'required|numeric',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $f) {
                if (! $f->isValid()) continue;
                $name = time() . '_' . 
                    \Illuminate\Support\Str::random(6) . '.' . $f->extension();
                $f->storeAs('products', $name, 'public');
                $images[] = $name;
            }
        }

        Product::create([
            'name' => $request->name,
            'price_usd' => $request->input('price_usd'),
            'brand' => $request->brand,
            'stock' => $request->input('stock', 0),
            'images' => $images,
        ]);

        return redirect('/products');
    }

    public function updateImage(Request $request, $id)
    {
        if (! auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'image' => 'required|image',
        ]);

        $product = Product::findOrFail($id);

        $slot = $request->input('slot', 'main');
        $imageName = time() . '_' . $slot . '.' . $request->image->extension();
        $request->image->storeAs('products', $imageName, 'public');

        $imgs = is_array($product->images) ? $product->images : (json_decode($product->images, true) ?: []);

        $index = 0;
        if ($slot === 'sub1') $index = 1;
        if ($slot === 'sub2') $index = 2;

        // delete previous stored file if it exists and is local
        if (!empty($imgs[$index]) && !Str::startsWith($imgs[$index], ['http://','https://'])) {
            Storage::disk('public')->delete('products/' . $imgs[$index]);
        }

        $imgs[$index] = $imageName;
        $product->images = $imgs;
        $product->save();

        return redirect()->back();
    }

    public function updateTags(Request $request, $id)
    {
        if (! auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $allowed = ['Floral','Woody','Luxury'];

        $selected = array_values(array_intersect($allowed, (array) $request->input('tags', [])));

        $product = Product::findOrFail($id);
        $product->tags = $selected;
        $product->save();

        return redirect()->back();
    }

    public function cleanMissing(Request $request)
    {
        if (! auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $products = Product::all();
        $missing = $products->filter(function ($p) {
            if (empty($p->image)) {
                return true;
            }
            if (Str::startsWith($p->image, ['http://', 'https://'])) {
                return false;
            }
            return ! Storage::disk('public')->exists('products/' . $p->image);
        });

        if ($request->query('confirm') == '1') {
            $count = $missing->count();
            foreach ($missing as $p) {
                $p->delete();
            }
            return redirect()->back()->with('status', "Deleted {$count} products with missing images.");
        }

        // show simple list so admin can confirm
        return view('admin.clean-products', ['missing' => $missing]);
    }

    public function destroy(Request $request, $id)
    {
        if (! auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $product = Product::findOrFail($id);

        // delete associated files if present (images array)
        $imgs = is_array($product->images) ? $product->images : (json_decode($product->images, true) ?: []);
        foreach ($imgs as $img) {
            if (! empty($img) && !Str::startsWith($img, ['http://','https://']) && Storage::disk('public')->exists('products/' . $img)) {
                Storage::disk('public')->delete('products/' . $img);
            }
        }
        $product->delete();

        return redirect()->back()->with('status', 'Product deleted');
    }
}