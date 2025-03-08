<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name', 
        'description', 
        'main_photo_url', 
        'instagram_link'
    ];

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function galleries()
    {
        return $this->hasMany(ProductGallery::class);
    }
    
}
