<?php

use Illuminate\Database\Seeder;
use App\ProductCat;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productcats = ['Lengan Pendek', 'Lengan Panjang', 'Raglan 3/4', 'Raglan Pendek', 'Raglan Panjang'];
        foreach($productcats as $p) {
            echo "importing $p ...\n";
            $pc = new ProductCat();
            $pc->name = $p;
            $pc->save();
        }
        
    }
}
