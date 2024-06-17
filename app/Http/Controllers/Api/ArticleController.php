<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Tentukan jumlah item per halaman
        $perPage = $request->query('per_page', 10);

        // Ambil artikel dengan paginasi
        $articles = Article::orderBy("created_at", "desc")->paginate($perPage);

        // Kembalikan respon dengan data paginasi
        return success([
            'items' => $articles->items(),
            'page' => $articles->currentPage(),
            'perPage' => $articles->perPage(),
            'totalItems' => $articles->total(),
            'totalPages' => $articles->lastPage(),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'title' => 'required|unique:articles,title,except,id',
            'description' => 'required',
            'thumbnail' => 'image|mimes:png,jpg|max:2048',
            'image' => 'image|mimes:png,jpg|max:2048',
        ]);
        $validateData['user_id'] = $request->user()->id;
        $article = Article::create($validateData);
        $files = ['thumbnail', 'image'];
        foreach ($files as $file) {
            if($request->hasFile($file)) {
                $filePath = $request->file($file)->store("articles/", 'public');
                $article->$file = 'storage/' . $filePath;
            }
        }
        $article->save();

        return response()->json([
            'message' => 'Successfully created a new article'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $article = Article::find($id);

        return response()->json([
            'message' => '',
            'data'=> $article,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = $request->validate([
            'title' => 'nullable',
            'description' => 'nullable',
            'thumbnail' => 'nullable|mimes:png,jpg|max:2048',
            'image' => 'nullable|mimes:png,jpg|max:2048',
        ]);
        $article = Article::find($id);
        $article->update($validateData);
        $files = ['thumbnail', 'image'];
        foreach ($files as $file) {
            if($request->hasFile($file)) {
                if ($article->$file) {
                    Storage::disk('public')->delete($article->$file);
                }
                $filePath = $request->file($file)->store("articles", 'public');
                $article->$file = 'storage/' . $filePath;
            }
        }
        $article->save();
        return response()->json([
            'message' => 'Successfully updated the article',
            'data'=> $article
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            // Cari artikel berdasarkan ID atau gagal jika tidak ditemukan
            $article = Article::findOrFail($id);

            // Hapus artikel jika ditemukan
            $article->delete();

            // Kembalikan respon sukses
            return response()->json([
                'message' => 'Successfully deleted the article',
            ], 200);
        } catch (ModelNotFoundException $e) {
            // Kembalikan respon error jika artikel tidak ditemukan
            return response()->json([
                'message' => 'Article not found',
            ], 404);
        }
    }
}
