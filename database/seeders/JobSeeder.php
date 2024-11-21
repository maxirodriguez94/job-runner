<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Job;

class JobSeeder extends Seeder
{
    public function run()
    {
        
        Job::create([
            'class_name' => 'App\Jobs\ExampleJob',
            'method_name' => 'process',
            'parameters' => json_encode(['param1', 'param2']),
            'status' => 'running',
            'priority' => 1, 
            'retries' => 0,
            'started_at' => now(),
            'completed_at' => null,
            'error_message' => null,
        ]);

        Job::create([
            'class_name' => 'App\Jobs\ExampleJob',
            'method_name' => 'process',
            'parameters' => json_encode(['param3', 'param4']),
            'status' => 'completed',
            'priority' => 3, 
            'retries' => 1,
            'started_at' => now()->subHours(2),
            'completed_at' => now()->subHours(1),
            'error_message' => null,
        ]);

        Job::create([
            'class_name' => 'App\Jobs\AnotherJob',
            'method_name' => 'run',
            'parameters' => json_encode(['param5', 'param6']),
            'status' => 'failed',
            'priority' => 2, 
            'retries' => 3,
            'started_at' => now()->subHours(4),
            'completed_at' => null,
            'error_message' => 'Error de conexión',
        ]);
    }
}
