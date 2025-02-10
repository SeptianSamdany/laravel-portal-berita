<?php

namespace App\Http\Controllers;

use App\Models\ArticleNews;
use App\Models\Author;
use App\Models\BannerAdvertisement;
use App\Models\Category;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        $categories = Category::all(); 

        $articles = ArticleNews::with(['category'])
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(3)
        ->get(); 

        $featured_articles = ArticleNews::with(['category'])
        ->where('is_featured', 'featured')
        ->inRandomOrder()
        ->take(3)
        ->get(); 

        $authors = Author::all(); 

        $bannerads = BannerAdvertisement::where('is_active', 'active')
        ->where('type'. 'banner')
        ->inRandomOrder()
        // ->take(1)
        ->first(); 

        $entertainment_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Entertainment'); 
        })
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(6)
        ->get();

        $entertainment_featured_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Entertainment'); 
        })
        ->where('is_featured', 'featured')
        ->inRandomOrder()
        ->first();

        return view('front.index', compact('entertainment_featured_articles','entertainment_articles', 'categories', 'articles', 'authors', 
        'featured_articles', 'bannerads')); 
    }

    public function category(Category $category)
    {
        $categories = Category::all(); 

        $bannerads = BannerAdvertisement::where('is_active', 'active')
        ->where('type'. 'banner')
        ->inRandomOrder()
        ->first(); 

        return view('front.category', compact('category', 'categories', 'bannerads')); 
    }
}