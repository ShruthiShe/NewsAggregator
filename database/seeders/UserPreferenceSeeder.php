<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\UserPreference;

class UserPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Fetch all users
        $users = User::all();

        foreach ($users as $user) {
            UserPreference::create([
                'user_id' => $user->id, // Link preference to user
                'news_sources' => ['BBC', 'CNN'], // Default sources
                'categories' => ['Technology', 'Health'], // Default categories
                'authors' => ['John Doe', 'Jane Smith'], // Default authors
            ]);
        }
    
    }
}
