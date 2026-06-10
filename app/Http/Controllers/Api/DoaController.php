<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doa;
use Illuminate\Http\Request;

class DoaController extends Controller
{
    public function index(Request $request)
    {
        $query = Doa::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('terjemahan', 'like', "%{$search}%")
                  ->orWhere('arab', 'like', "%{$search}%");
            });
        }

        if ($kategori = $request->get('kategori')) {
            $query->where('kategori', $kategori);
        }

        $query->orderBy('id', 'asc');

        // Ambil per_page dari request, default 20, maksimal 100
        $perPage = min((int) $request->get('per_page', 20), 100);

        $data = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $data->items(),
            'meta'    => [
                'current_page' => $data->currentPage(),
                'last_page'    => $data->lastPage(),
                'total'        => $data->total(),
                'per_page'     => $data->perPage(),
            ],
        ]);
    }

    public function show(int $id)
    {
        $doa = Doa::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $doa,
        ]);
    }
}