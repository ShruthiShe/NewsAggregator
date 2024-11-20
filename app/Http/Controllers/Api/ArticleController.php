<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;



class ArticleController extends Controller
{

    //fetch articles with support for pagination
    public function index()
    {
        $articles = Article::paginate(10); // 10 articles per page
        return response()->json($articles);
    }


       // Implement search functionality to allow filtering articles by keyword, date, category, and source.
       public function search(Request $request)
       {
        //    $data = Article::query();
        $data = DB::table('articles');
   
           if ($request->has('keyword')) {
               $data->where('title', 'LIKE', '%' . $request->keyword . '%')
                     ->orWhere('content', 'LIKE', '%' . $request->keyword . '%');
           }
   
           if ($request->has('published_date')) {
               $data->whereDate('published_date', $request->published_date);
           }
   
           if ($request->has('category')) {
               $data->where('category', $request->category);
           }
   
           if ($request->has('source')) {
               $data->where('source', $request->source);
           }
   

           $articles = $data->paginate(10); // 10 articles per page
           return response()->json($articles);
       }

            // Retrieve a single article's details
        public function show($id)
        {
                $article = Article::find($id);
                return response()->json($article);
         }



        // Fetch and store articles from NewsAPI
        public function fetchAndStoreNewsAPI()
        {
        
            // Make the API request to NewsAPI
            $response = Http::get('https://newsapi.org/v2/top-headlines', [
                'apiKey' => config('app.news_api_key', env('NEWSAPI_KEY')),  // Use the API key from .env
                'country' => 'us',
            ]);
        
            // Decode the JSON response
            $data = $response->json();
        
            // Check if articles are present
            if (isset($data['articles'])) {
                $articles = $data['articles'];
                foreach ($articles as $article) {
                    $url = substr($article['url'], 0, 255); // Truncate the URL to 255 characters
                    if (!Article::where('url', $url)->exists()) {
                        Article::create([
                            'title' => $article['title'],
                            'content' => $article['content'] ?? 'No content available',
                            'category' => $article['category'] ?? 'Health',
                            'source' => $article['source']['name'] ?? 'Unknown Source',
                            'author' => $article['author'] ?? null,
                            'url' => $url,
                            'published_date' => Carbon::parse($article['publishedAt'])->toDateTimeString(),
                        ]);
                    }
                }
                
        
                return response()->json(['message' => count($articles) . ' articles stored successfully...']);
            } else {
                return response()->json(['error' => 'No articles found or an error occurred.'], 400);
            }
        }


        // Fetch and store articles from The Guardian
        public function fetchAndStoreGuardianAPI()
        {
            $response = Http::get('https://content.guardianapis.com/search', [
                'api-key' => config('app.guardian_key', env('GUARDIAN_KEY')),
                'section' => 'world',
            ]);

            $articles = $response->json()['response']['results'];

            foreach ($articles as $article) {
                Article::updateOrCreate(
                    ['url' => $article['webUrl']], // Match based on the unique 'url'
                    [
                        'title' => $article['webTitle'],
                        'content' => $article['fields']['body'] ?? null,
                        'source' => 'The Guardian',
                        'author' => $article['fields']['byline'] ?? null,
                        'published_date' => Carbon::parse($article['webPublicationDate'])->toDateTimeString(),
                        'category' => $article['sectionName'],
                    ]
                );
            }
            
            // dd($articles);
            return response()->json(['message' => 'Articles from The Guardian stored successfully.']);
        }

        // Fetch and store articles from New York Times
        public function fetchAndStoreNYTimesAPI()
        {
            $response = Http::get('https://api.nytimes.com/svc/topstories/v2/home.json', [
                'api-key' => config('app.nyt_api_key', env('NYT_API_KEY')),
            ]);
        
            \Log::info('NY Times API Response:', $response->json());
        
            $articles = $response->json()['results'] ?? [];
        
            foreach ($articles as $article) {
                Article::updateOrCreate(
                    ['url' => $article['url']], 
                    [
                        'title' => $article['title'],
                        'content' => $article['abstract'],
                        'source' => 'New York Times',
                        'author' => $article['byline'] ?? null,
                        'published_date' => Carbon::parse($article['published_date'])->toDateTimeString(),
                        'category' => $article['section'],
                    ]
                );
            }
        
            return response()->json(['message' => 'Articles from New York Times stored successfully.']);
        }
    

}
