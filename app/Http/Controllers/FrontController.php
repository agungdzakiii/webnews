<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Article;
use App\Models\Author;
use App\Models\BannerAd;


use Illuminate\Http\Request;

class FrontController extends Controller
{
    //
    public function index(){
        $category = Category::all();

        $article = Article::with(['category'])
        ->where('is_featured','not_featured')
        ->latest()
        ->take(3)
        ->get();

        $featured_article = Article::with(['category'])
        ->where('is_featured','featured')
        ->inRandomOrder()
        ->take(3)
        ->get();

        $author = Author::all();

        $banner_ads = BannerAd::where('is_active', 'active')
        ->where('type','banner')
        ->first();

        $sport_article = Article::whereHas('category', function ($query) {
            $query->where('name', 'Sports');
        })
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(6)
        ->get();

        $sport_featured_article = Article::whereHas('category', function ($query) {
            $query->where('name', 'Sports');
        })
        ->where('is_featured', 'featured')
        ->inRandomOrder()
        ->first();

        $foods_article = Article::whereHas('category', function ($query) {
            $query->where('name', 'Foods');
        })
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(6)
        ->get();

        $foods_featured_article = Article::whereHas('category', function ($query) {
            $query->where('name', 'Foods');
        })
        ->where('is_featured', 'featured')
        ->inRandomOrder()
        ->first();

        $health_article = Article::whereHas('category', function ($query) {
            $query->where('name', 'Health');
        })
        ->where('is_featured', 'not_featured')
        ->latest()
        ->take(6)
        ->get();

        $health_featured_article = Article::whereHas('category', function ($query) {
            $query->where('name', 'Health');
        })
        ->where('is_featured', 'featured')
        ->inRandomOrder()
        ->first();

        return view('front.index', compact('category','article','author', 
        'featured_article', 'banner_ads', 'sport_article', 'sport_featured_article', 'foods_article', 'foods_featured_article','health_article','health_featured_article'));
    }

    public function category(Category $category){
        $categories = Category::all();
        $banner_ads = BannerAd::where('is_active', 'active')
        ->where('type','banner')
        ->inRandomOrder()
        ->first();
        return view('front.category', compact('category', 'categories', 'banner_ads'));
    }

    public function author(Author $author) {
        $categories = Category::all();
        $banner_ads = BannerAd::where('is_active', 'active')
        ->where('type','banner')
        ->inRandomOrder()
        ->first();
        return view('front.author', compact('categories','author', 'banner_ads'));
    }

    public function search(Request $request){
        
        $request->validate([
            'keyword' => ['required', 'string', 'max:255'],
        ]);

        $categories = Category::all();

        $keyword = $request->keyword;

        $article = Article::with(['category', 'author'])
        ->where('name', 'like', '%' . $keyword . '%')->paginate(6);

        return view('front.search', compact('article','keyword','categories'));
    }

    public function details(Article $article){
        $categories = Category::all();
        
        $articles = Article::with(['category'])
        ->where('is_featured','not_featured')
        ->where('id', '!=', $article->id)
        ->latest()
        ->take(3)
        ->get();

        $banner_ads = BannerAd::where('is_active', 'active')
        ->where('type','banner')
        ->inRandomOrder()
        ->first();

        $square_ads = BannerAd::where('type', 'square')
        ->where('is_active','active')
        ->inRandomOrder()
        ->take(2)
        ->get();

        if($square_ads->count() < 2){
            $square_ads_1 = $square_ads->first();
            $square_ads_2 = null;
        } else {
            $square_ads_1 = $square_ads->get(0);
            $square_ads_2 = $square_ads->get(1);
        }

        $author_news = Article::where('author_id', $article->author_id)
        ->where('id','!=', $article->id)
        ->inRandomOrder()
        ->get();

        return view('front.details', compact('author_news','square_ads','square_ads_1', 'square_ads_2','article', 'categories', 'banner_ads'));
    }
}
