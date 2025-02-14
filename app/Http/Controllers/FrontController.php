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
        ->where('type', 'banner')
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

        $business_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Business'); 
        })
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(6)
        ->get(); 

        $business_featured_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Business'); 
        })
        ->where('is_featured', 'featured')
        ->inRandomOrder()
        ->first();

        $automotive_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Automotive'); 
        })
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(6)
        ->get(); 

        $automotive_featured_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Automotive'); 
        })
        ->where('is_featured', 'featured')
        ->inrandomOrder()
        ->first();

        return view('front.index', compact('entertainment_featured_articles','entertainment_articles', 'categories', 'articles', 'authors', 
        'featured_articles', 'bannerads', 'business_articles', 'business_featured_articles', 'automotive_articles', 'automotive_featured_articles')); 
    }

    public function category(Category $category)
    {
        $categories = Category::all(); 

        $bannerads = BannerAdvertisement::where('is_active', 'active')
        ->where('type', 'banner')
        ->inRandomOrder()
        ->first(); 

        return view('front.category', compact('category', 'categories', 'bannerads')); 
    }

    public function author(Author $author) 
    {
        $authors = Author::all();

        $categories = Category::all(); 

        $bannerads = BannerAdvertisement::where('is_active', 'active')
        ->where('type', 'banner')
        ->inRandomOrder()
        ->first(); 

        return view('front.author', compact('author', 'authors', 'categories', 'bannerads'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'keyword' => ['required', 'string', 'max:255'],
        ]);

        $categories = Category::all();

        $keyword = $request->keyword;

        $articles = ArticleNews::with(['category', 'author'])
        ->where('name', 'like', '%' . $keyword . '%')->paginate(6);

        return view('front.search', compact('keyword', 'articles', 'categories'));
    }

    public function details(ArticleNews $articleNews) {
        $categories = Category::all();

        $articles = ArticleNews::with(['category'])
        ->where('is_featured', 'not_featured')
        ->where('id', '!=', $articleNews->id)
        ->latest()
        ->take(3)
        ->get();

        $bannerads = BannerAdvertisement::where('is_active', 'active')
        ->where('type', 'banner')
        ->inRandomOrder()
        ->first();

        $bannerads = BannerAdvertisement::where('is_active', 'active')
        ->where('type', 'banner')
        ->inRandomOrder()
        ->first();

        $bannerads_square = BannerAdvertisement::where('type', 'banner')
        ->where('is_active', 'active')
        ->inRandomOrder()
        ->take(2)
        ->get();

        if ($bannerads_square->count() < 2) {
            $bannerads_square_1 = $bannerads_square->first();
            $bannerads_square_2 = $bannerads_square->first();
        } else {
            $bannerads_square_1 = $bannerads_square->get(0);
            $bannerads_square_2 = $bannerads_square->get(1);
        }

        $author_news = ArticleNews::where('author_id', $articleNews->author_id)
        ->where('id', '!=', $articleNews->id)
        ->latest()
        ->get();

        return view('front.details', compact('articleNews', 'categories', 'bannerads', 'articles', 'bannerads_square_1', 'bannerads_square_2'));
    }
}