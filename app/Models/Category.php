<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\hasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use hasFactory, SoftDeletes; 

    protected $fillable = [
        'name', 
        'slug', 
        'icon'
    ]; 

    public function news(): HasMany
    {
        return $this->hasMany(ArticleNews::class); 
    }
}   
