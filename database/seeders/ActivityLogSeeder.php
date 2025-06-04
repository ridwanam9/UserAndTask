<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'create_user',
            'description' => 'Admin created user: XYZ',
            'logged_at' => now(),
        ]);

    }
}
