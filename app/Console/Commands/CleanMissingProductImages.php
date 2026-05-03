<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class CleanMissingProductImages extends Command
{
    protected $signature = 'products:clean-missing-images {--delete-orphan-files : also delete files on disk that are orphaned}';

    protected $description = 'Remove products that have no image or whose image file is missing; optionally remove orphan files.';

    public function handle()
    {
        $this->info('Scanning products...');

        $all = Product::all();
        $toDelete = $all->filter(function ($p) {
            return empty($p->image) || ! Storage::disk('public')->exists('products/' . $p->image);
        });

        if ($toDelete->isEmpty()) {
            $this->info('No products with missing images found.');
            return 0;
        }

        $this->info('Found ' . $toDelete->count() . ' products with missing images:');
        foreach ($toDelete as $p) {
            $this->line("- [{$p->id}] {$p->name} (image: {$p->image})");
        }

        if ($this->confirm('Delete these products from database?')) {
            foreach ($toDelete as $p) {
                $p->delete();
            }
            $this->info('Deleted.');
        } else {
            $this->info('Aborted.');
        }

        if ($this->option('delete-orphan-files')) {
            $this->info('Deleting orphan files (images not referenced by any product)...');
            $files = Storage::disk('public')->files('products');
            foreach ($files as $file) {
                $name = basename($file);
                $exists = Product::where('image', $name)->orWhere('sub_image_1', $name)->orWhere('sub_image_2', $name)->exists();
                if (! $exists) {
                    Storage::disk('public')->delete($file);
                    $this->line('Deleted file: ' . $file);
                }
            }
        }

        return 0;
    }
}
