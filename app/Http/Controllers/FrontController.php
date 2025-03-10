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

        $politik_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Politik'); 
        })
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(6)
        ->get();

        $politik_featured_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Politik'); 
        })
        ->where('is_featured', 'featured')
        ->inRandomOrder()
        ->first();

        $teknologi_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Teknologi'); 
        })
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(6)
        ->get(); 

        $teknologi_featured_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Teknologi'); 
        })
        ->where('is_featured', 'featured')
        ->inRandomOrder()
        ->first();

        $kesehatan_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Kesehatan'); 
        })
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(6)
        ->get(); 

        $kesehatan_featured_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Kesehatan'); 
        })
        ->where('is_featured', 'featured')
        ->inrandomOrder()
        ->first();

        $olaharaga_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Olahraga'); 
        })
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(6)
        ->get();

        $olahraga_featured_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Olahraga'); 
        })
        ->where('is_featured', 'featured')
        ->inrandomOrder()
        ->first();

        $politik_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Politik'); 
        })
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(6)
        ->get();

        $politik_featured_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Politik'); 
        })
        ->where('is_featured', 'featured')
        ->inrandomOrder()
        ->first();

        $pariwisata_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Pariwisata'); 
        })
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(6)
        ->get();

        $pariwisata_featured_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Pariwisata'); 
        })
        ->where('is_featured', 'featured')
        ->inrandomOrder()
        ->first();

        $bisnis_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Bisnis'); 
        })
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(6)
        ->get();

        $bisnis_featured_articles = ArticleNews::whereHas('category', function ($query) {
            $query->where('name', 'Bisnis'); 
        })
        ->where('is_featured', 'featured')
        ->inrandomOrder()
        ->first();

        

        return view('front.index', compact('politik_featured_articles','politik_articles', 'categories', 'articles', 'authors', 
        'featured_articles', 'bannerads', 'kesehatan_articles', 'kesehatan_featured_articles', 'teknologi_articles', 'teknologi_featured_articles', 'olaharaga_articles', 'olahraga_featured_articles', 'pariwisata_articles', 'pariwisata_featured_articles', 'bisnis_articles', 'bisnis_featured_articles')); 
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

        $bannerads_square = BannerAdvertisement::where('type', 'square')
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

        return view('front.details', compact('articleNews', 'categories', 'bannerads', 'articles', 'bannerads_square_1', 'bannerads_square_2', 'author_news'));
    }
}