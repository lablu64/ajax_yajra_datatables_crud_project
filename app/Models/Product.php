<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];  
    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }

    public function subcategory(){
        return $this->belongsTo(SubCategory::class,'subcategory_id');
    }

    public function childcategory(){
        return $this->belongsTo(ChildCategory::class,'childcategory_id');
    }

    public function brand(){
        return $this->belongsTo(Brand::class,'brand_id');
    }

    public function pickuppoint(){
        return $this->belongsTo(Pickup::class,'pickup_point_id');
    }


}
