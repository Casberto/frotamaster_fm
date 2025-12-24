<?php
use Illuminate\Database\Capsule\Manager as Capsule;

require 'vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

try {
    echo "Attempting to check 'migrations' table...\n";
    \DB::table('migrations')->limit(1)->get();
    echo "'migrations' table is OK.\n";
} catch (\Exception $e) {
    echo "Error checking 'migrations' table: " . $e->getMessage() . "\n";
}

try {
    echo "Attempting to drop 'sessions' table...\n";
    \Schema::dropIfExists('sessions');
    echo "'sessions' table dropped (if it existed).\n";
} catch (\Exception $e) {
    echo "Error dropping 'sessions' table: " . $e->getMessage() . "\n";
}

try {
    echo "Attempting to create 'sessions' table...\n";
    \Schema::create('sessions', function ($table) {
        $table->string('id')->primary();
        $table->foreignId('user_id')->nullable()->index();
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->longText('payload');
        $table->integer('last_activity')->index();
    });
    echo "'sessions' table created successfully.\n";
} catch (\Exception $e) {
    echo "Error creating 'sessions' table: " . $e->getMessage() . "\n";
}
