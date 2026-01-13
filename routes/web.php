<?php

use App\Livewire\ProductList;
use Illuminate\Support\Facades\Route;

Route::get('/', ProductList::class);

// Test route to check if application is working
Route::get('/test', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Laravel application is working!',
        'timestamp' => now()->toISOString(),
    ]);
});
