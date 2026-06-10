<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hadist;
use Illuminate\Http\Request;

class HadistController extends Controller
{
    public function index(Request $request)
    {
        $query = Hadist::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('terjemahan', 'like', "%{$search}%")
                  ->orWhere('arab', 'like', "%{$search}%");
            });
        }

        if ($kategori = $request->get('kategori')) {
            $list = explode(',', $kategori);
            $query->whereIn('kategori', $list);
        }

        if ($kitab = $request->get('kitab')) {
            $query->where('kitab', $kitab);
        }

        $query->orderBy('nomor', 'asc');

        $data = $query->paginate($request->get('per_page', 12));

        return response()->json([
            'success' => true,
            'data'    => $data->items(),
            'meta'    => [
                'current_page' => $data->currentPage(),
                'last_page'    => $data->lastPage(),
                'total'        => $data->total(),
                'per_page'     => $data->perPage(),
            ],
        ])->header('Access-Control-Allow-Origin', '*')
          ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
          ->header('Access-Control-Allow-Headers', '*');
    }

    public function show(int $id)
    {
        $hadist = Hadist::findOrFail($id);

        $prev = Hadist::where('nomor', '<', $hadist->nomor)
            ->orderBy('nomor', 'desc')
            ->select('id', 'nomor', 'judul')
            ->first();

        $next = Hadist::where('nomor', '>', $hadist->nomor)
            ->orderBy('nomor', 'asc')
            ->select('id', 'nomor', 'judul')
            ->first();

        return response()->json([
            'success' => true,
            'data'    => [
                'data' => $hadist,
                'prev' => $prev,
                'next' => $next,
            ],
        ])->header('Access-Control-Allow-Origin', '*')
          ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
          ->header('Access-Control-Allow-Headers', '*');
    }
}