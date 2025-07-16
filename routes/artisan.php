<?php
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Artisan;

Route::get('/clear-cache', function () {
   
   Artisan::call('cache:clear');
   Artisan::call('route:clear');
   Artisan::call('view:clear');
   $route = Artisan::call('route:list');
    return Artisan::output();
   return "Cache cleared successfully";
});
