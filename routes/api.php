<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HadistController;
use App\Http\Controllers\Api\DoaController;
use App\Models\Hadist;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ===============================
// HADIST API
// ===============================
Route::get('/hadist',      [HadistController::class, 'index']);
Route::get('/hadist/{id}', [HadistController::class, 'show']);

// ===============================
// Doa API
// ===============================
Route::get('/doa',      [DoaController::class, 'index']);
Route::get('/doa/{id}', [DoaController::class, 'show']);

// ===============================
// FILTER API (KITAB & KATEGORI)
// ===============================
Route::get('/filter', function () {
    return [
        'kitab' => Hadist::whereNotNull('kitab')
            ->where('kitab', '!=', '')
            ->select('kitab')
            ->distinct()
            ->pluck('kitab'),

        'kategori' => Hadist::whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->select('kategori')
            ->distinct()
            ->pluck('kategori'),
    ];
});

// ===============================
// KATEGORI API
// ===============================
Route::get('/kategori', function () {
    $items = Hadist::whereNotNull('kategori')
        ->where('kategori', '!=', '')
        ->select('kategori')
        ->distinct()
        ->orderBy('kategori')
        ->pluck('kategori')
        ->values()
        ->map(fn($nama) => ['id' => $nama, 'nama' => $nama]);

    return response()->json(['data' => $items]);
});

// ===============================
// KITAB API
// ===============================
Route::get('/kitab', function () {
    $items = Hadist::whereNotNull('kitab')
        ->where('kitab', '!=', '')
        ->select('kitab')
        ->distinct()
        ->orderBy('kitab')
        ->pluck('kitab')
        ->values()
        ->map(fn($nama) => ['id' => $nama, 'nama' => $nama]);

    return response()->json(['data' => $items]);
});

// ===============================
// TTS (Text to Speech) API
// ===============================
Route::get('/tts', function (Request $request) {
    $text = $request->query('text', '');
    $lang = $request->query('lang', 'ar');

    $url = "https://translate.google.com/translate_tts?ie=UTF-8&q=" . urlencode($text) . "&tl={$lang}&client=tw-ob";

    $context = stream_context_create([
        'http' => [
            'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n",
            'timeout' => 10,
        ]
    ]);

    $audio = @file_get_contents($url, false, $context);

    if ($audio === false) {
        return response()->json(['error' => 'Gagal mengambil audio'], 500);
    }

    return response($audio, 200)
        ->header('Content-Type', 'audio/mpeg');
});