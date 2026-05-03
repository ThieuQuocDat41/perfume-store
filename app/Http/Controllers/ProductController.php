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
        // Load all products but include those with external image URLs
        $products = Product::all()->filter(function ($p) {
            if (! $p->image) {
                return false;
            }
            if (($p->stock ?? 0) <= 0) {
                return false;
            }
            if (Str::startsWith($p->image, ['http://', 'https://'])) {
                return true;
            }
            return Storage::disk('public')->exists('products/' . $p->image);
        })->values();

        return view('products.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $related = Product::whereNotNull('image')
            ->where('stock', '>', 0)
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
            'price' => 'required|numeric',
            'image' => 'required|image',
        ]);

        $imageName = time() . '.' . $request->image->extension();

        $request->image->storeAs('products', $imageName, 'public');

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'brand' => $request->brand,
            'image' => $imageName,
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

        if ($slot === 'main') {
            if ($product->image) {
                Storage::disk('public')->delete('products/' . $product->image);
            }
            $product->image = $imageName;
        } elseif ($slot === 'sub1') {
            if ($product->sub_image_1) {
                Storage::disk('public')->delete('products/' . $product->sub_image_1);
            }
            $product->sub_image_1 = $imageName;
        } elseif ($slot === 'sub2') {
            if ($product->sub_image_2) {
                Storage::disk('public')->delete('products/' . $product->sub_image_2);
            }
            $product->sub_image_2 = $imageName;
        }

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

        // delete associated files if present
        foreach (['image', 'sub_image_1', 'sub_image_2'] as $col) {
            if (! empty($product->{$col}) && Storage::disk('public')->exists('products/' . $product->{$col})) {
                Storage::disk('public')->delete('products/' . $product->{$col});
            }
        }

        $product->delete();

        return redirect()->back()->with('status', 'Product deleted');
    }
}