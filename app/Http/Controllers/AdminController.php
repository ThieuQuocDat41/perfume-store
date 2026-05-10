<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function usersIndex()
    {
        $users = User::orderBy('id','desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function usersShow($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function usersEdit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function usersUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'role' => 'nullable|string|in:user,admin'
        ]);

        $user->fill($data);
        $user->save();

        return redirect(url('/admin/users'))->with('status', 'User updated');
    }

    public function usersDestroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect(url('/admin/users'))->with('status', 'User deleted');
    }

    // Products
    public function productsIndex()
    {
        $products = Product::orderBy('id','desc')->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function productsEdit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    public function productsUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'price_usd' => 'required|numeric',
            'stock' => 'nullable|integer',
            'short_description' => 'nullable|string',
            'top_notes' => 'nullable|string',
            'heart_notes' => 'nullable|string',
            'base_notes' => 'nullable|string',
            'gender' => 'nullable|in:male,female,unisex',
            'images_raw' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'tags_raw' => 'nullable|string'
        ]);

        // images: comma separated URLs or paths -> array
        $imgs = is_array($product->images) ? $product->images : (json_decode($product->images, true) ?: []);
        if (!empty($data['images_raw'])) {
            $parsed = array_values(array_filter(array_map('trim', explode(',', $data['images_raw']))));
            $imgs = array_values(array_filter(array_merge($imgs, $parsed)));
        }

        // handle removal of existing images (checkboxes named remove_images[])
        if ($request->filled('remove_images')) {
            $toRemove = (array) $request->input('remove_images', []);
            foreach ($toRemove as $rem) {
                // delete local files only
                if (!Str::startsWith($rem, ['http://','https://']) && Storage::disk('public')->exists('products/' . $rem)) {
                    Storage::disk('public')->delete('products/' . $rem);
                }
                $imgs = array_values(array_filter($imgs, function($v) use ($rem){ return $v !== $rem; }));
            }
        }

        // handle uploaded image files (append)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $f) {
                if (! $f->isValid()) continue;
                $name = time() . '_' . Str::random(6) . '.' . $f->extension();
                $f->storeAs('products', $name, 'public');
                $imgs[] = $name;
            }
        }

        $data['images'] = $imgs;

        // convert comma separated tags into array
        if (!empty($data['tags_raw'])) {
            $tags = array_values(array_filter(array_map('trim', explode(',', $data['tags_raw']))));
            $data['tags'] = $tags;
        } else {
            $data['tags'] = [];
        }

        // remove raw fields before filling
        unset($data['images_raw'], $data['tags_raw']);

        $product->fill($data);
        $product->save();

        return redirect(url('/admin/products'))->with('status', 'Product updated');
    }

    // Vouchers management
    public function vouchersIndex()
    {
        $vouchers = \App\Models\Voucher::orderBy('id','desc')->paginate(20);
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function vouchersCreate()
    {
        return view('admin.vouchers.create');
    }

    public function vouchersStore(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:100|unique:vouchers,code',
            'percent' => 'required|integer|min:1|max:99'
        ]);

        \App\Models\Voucher::create([
            'code' => strtoupper(trim($data['code'])),
            'percent' => $data['percent']
        ]);

        return redirect(url('/admin/vouchers'))->with('status', 'Voucher created');
    }

    public function vouchersDestroy($id)
    {
        $v = \App\Models\Voucher::findOrFail($id);
        $v->delete();
        return redirect(url('/admin/vouchers'))->with('status', 'Voucher deleted');
    }
}
