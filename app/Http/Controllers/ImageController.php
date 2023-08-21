<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Article;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function create(Request $request)
    {
        $image = new Image();
        $image->name = $request->name;
        $image->description = $request->description;
        
        // Загрузка файла
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('images', $fileName);
            $image->file_url = $fileName;
        }
        
        $image->save();
        
        // Привязка изображения к статье, если указан article_id
        if ($request->has('article_id')) {
            $article = Article::find($request->article_id);
            if ($article) {
                $article->images()->attach($image->id);
            }
        }
        
        return response()->json(['message' => 'Image created successfully']);
    }
    
    // Чтение изображения по ID
    public function read($id)
    {
        $image = Image::find($id);
        
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }
        
        return response()->json($image);
    }
    
    // Обновление изображения
    public function update(Request $request, $id)
    {
        $image = Image::find($id);
        
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }
        
        $image->name = $request->name;
        $image->description = $request->description;
        
        // Замена файла
        if ($request->hasFile('file')) {
            // Удаление старого файла
            Storage::delete('images/' . $image->file_url);
            
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('images', $fileName);
            $image->file_url = $fileName;
        }
        
        $image->save();
        
        return response()->json(['message' => 'Image updated successfully']);
    }
    
    // Удаление изображения
    public function delete($id)
    {
        $image = Image::find($id);
        
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }
        
        // Удаление файла
        Storage::delete('images/' . $image->file_url);
        
        // Удаление связей с статьями
        $image->articles()->detach();
        
        $image->delete();
        
        return response()->json(['message' => 'Image deleted successfully']);
    }
}
