<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function create(Request $request)
    {
        $article = Article::create($request->all());
        return response()->json($article, 201);
    }

    public function show($id)
    {
        $article = Article::findOrFail($id);
        return response()->json($article);
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        $article->update($request->all());
        return response()->json($article, 200);
    }

    public function delete($id)
    {
        Article::findOrFail($id)->delete();
        return response()->json('Статья успешно удалена');
    }

    public function index()
    {
        $articles = Article::paginate(10);
        return response()->json($articles);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $articles = Article::where('title', 'LIKE', "%$keyword%")
                      ->orWhere('content', 'LIKE', "%$keyword%")
                      ->get();
        return response()->json($articles);
    }
    
}
