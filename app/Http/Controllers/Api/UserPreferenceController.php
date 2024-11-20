<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserPreference;
use App\Models\Article;

class UserPreferenceController extends Controller
{
        // get user preferences
        public function getPreferences(Request $request)
        {
            $userId = $request->user()->id;
            $preferences = UserPreference::where('user_id', $userId)->first();
    
            if (!$preferences) {
                return response()->json(['message' => 'No preferences found'], 404);
            }
    
            return response()->json($preferences);
        }



         // Set user preferences
    public function setPreferences(Request $request)
    {

       

        $request->validate([
            'news_sources' => 'array',
            'categories' => 'array',
            'authors' => 'array',
        ]);

        $userId = $request->user()->id;
        // dd($userId);

        $preferences = UserPreference::updateOrCreate(
            ['user_id' => $userId],
            [
                'news_sources' => $request->input('news_sources', []),
                'categories' => $request->input('categories', []),
                'authors' => $request->input('authors', []),
            ]
        );

        return response()->json($preferences);
    }


    //fetch a personalized news feed based on user preferences.
    public function getPersonalizedFeed(Request $request)
    {
        $userId = $request->user()->id;
    
        $preferences = UserPreference::where('user_id', $userId)->first();
    
        if (!$preferences) {
            return response()->json(['message' => 'No preferences found'], 404);
        }
    
        $articlesQuery = Article::query();
    
        if (!empty($preferences->news_sources)) {
            $articlesQuery->whereIn('source', $preferences->news_sources);
        }
    
        if (!empty($preferences->categories)) {
            $articlesQuery->whereIn('category', $preferences->categories);
        }
    
        if (!empty($preferences->authors)) {
            $articlesQuery->whereIn('author', $preferences->authors);
        }
    
        $articles = $articlesQuery->paginate(5);
    
        return response()->json($articles);
    }
    

}
