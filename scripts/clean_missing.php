<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

$missing = Product::all()->filter(function ($p) {
    return empty($p->image) || ! Storage::disk('public')->exists('products/' . $p->image);
});

echo "Found " . $missing->count() . " missing products\n";

foreach ($missing as $p) {
    foreach (['image', 'sub_image_1', 'sub_image_2'] as $col) {
        if (! empty($p->{$col}) && Storage::disk('public')->exists('products/' . $p->{$col})) {
            Storage::disk('public')->delete('products/' . $p->{$col});
            echo "Deleted file: " . $p->{$col} . "\n";
        }
    }
    $id = $p->id;
    $p->delete();
    echo "Deleted product: {$id}\n";
}

echo "Done.\n";
