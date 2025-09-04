<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Unit;
use App\Models\ProductUnit;

class SyncProductUnitSby extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tools:sync-product-unit-surabaya';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync product units for product in surabaya unit';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $unitsSby = [
            'KB-SURABAYA' => '[KB TK SBY]',
            'TK-SURABAYA' => '[KB TK SBY]',
            'SD-SURABAYA' => '[SD SBY]',
            'SMP-SURABAYA' => '[SMP SBY]',
            'SMA-SURABAYA' => '[SMA SBY]',
        ];

        foreach ($unitsSby as $key => $value) {
            $units = Unit::where('name', 'like', '%'.$key.'%')
                ->pluck('id');

            $products = Product::where('name', 'like', '%'. $value .'%')
                ->get();
            
            foreach ($products as $product) {
                foreach ($units as $unit) {
                    $productUnit = ProductUnit::firstOrCreate([
                        'product_id' => $product->id,
                        'unit_id' => $unit
                    ]);
                }
            }
        }
    }
}
