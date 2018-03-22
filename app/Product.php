<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function productCat() {
        return $this->belongsTo('App\ProductCat');
    }
}