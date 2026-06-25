<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/docs');
});

Route::get('/docs', function () {
    return view('scribe.index');
});

Route::get('/docs.postman', function () {
    return response()->file(storage_path('app/scribe/collection.json'));
})->name('scribe.postman');

Route::get('/docs.openapi', function () {
    return response()->file(storage_path('app/scribe/openapi.yaml'));
})->name('scribe.openapi');