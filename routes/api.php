<?php

use App\Http\Controllers\GalleryController;
use Illuminate\Support\Facades\Route;

Route::get('/get-album-names', [GalleryController::class, "get_album_names"]);
Route::post('/create-album', [GalleryController::class, "create_album"]);
Route::post('/upload-file', [GalleryController::class, "upload_file"]);
Route::delete('/retract-file', [GalleryController::class, "retract_file"]);
Route::post('/submit-files', [GalleryController::class, "submit_files"]);
Route::post('/edit-album', [GalleryController::class, "edit_album"]);
Route::post('/delete-album', [GalleryController::class, "delete_album"]);
Route::post('/delete-album-options', [GalleryController::class, "delete_album_options"]);
