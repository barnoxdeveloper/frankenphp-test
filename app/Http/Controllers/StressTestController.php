<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StressTestController extends Controller
{
    /**
     * Handle the stress test request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(): \Illuminate\Http\JsonResponse
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        // Get app name and database connection
        $appName = config('app.name');
        $dbConnection = DB::connection()->getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME);
        $dbType = $dbConnection === 'mysql' ? 'mysql' : 'pgsql';

        // CPU Stress: Run Hash::make in a loop 15 times
        for ($i = 0; $i < 15; $i++) {
            Hash::make('benchmark-passwordd');
        }

        // Database I/O: Insert a record with 50KB random string
        $payload = Str::random(50000); // ~50KB random string
        DB::table('stress_logs')->insert([
            'app_id' => $appName,
            'db_type' => $dbType,
            'payload' => $payload,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Database I/O: Retrieve the latest 50 records
        $records = DB::table('stress_logs')
            ->orderBy('id', 'desc')
            ->limit(50)
            ->get();

        // Memory Stress: Generate a dummy collection of 10,000 objects
        $dummyCollection = collect();
        for ($i = 0; $i < 10000; $i++) {
            $dummyCollection->push([
                'id' => $i,
                'name' => 'Item ' . $i,
                'data' => Str::random(100),
                'timestamp' => now()->toDateTimeString(),
            ]);
        }

        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        $executionTime = round(($endTime - $startTime) * 1000, 2); // in milliseconds
        $memoryUsage = round(($endMemory - $startMemory) / 1024 / 1024, 2); // in MB

        return response()->json([
            'app_name' => $appName,
            'db_connection_used' => $dbConnection,
            'execution_time' => $executionTime . 'ms',
            'current_memory_usage' => $memoryUsage . 'MB',
            'records_retrieved' => $records->count(),
        ]);
    }
}
