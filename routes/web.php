<?php

use App\Http\Controllers\BillsController;
use Illuminate\Support\Facades\Route;
use App\Models\Bill;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/test', function() {
//     return view('test');
// });

Route::get('/print/{student}/pdf', [BillsController::class, 'printPdf'])->name('bills.pdf');
