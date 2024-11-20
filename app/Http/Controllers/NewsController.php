<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    public function allNews()
    {
        return view('news.index');
    }

    public function allNewsData()
    {
        $newsList = News::all();
        return response()->json($newsList);
    }
    public function showCreate()
    {
        return view('news.create');
    }

    public function storeNews(Request $request)
    {
        $validatedData = $request->validate([
            'description' => 'required|string',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->hashName();
            $request->file('image')->storeAs('news_images', $imagePath, 'public');
        }

        $news = News::create([
            'user_id' => auth()->id(),
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return response()->json(['message' => 'News added successfully!'], 200);
    }

    public function getNewsData()
    {
        $news = News::with('user')->get();
        return response()->json($news);
    }

    public function deleteNews($id)
    {
        // Find the news record by ID
        $news = News::find($id);

        if ($news) {
            // If there is an associated image, delete it from the storage
            if ($news->image) {
                Storage::disk('public')->delete('news_images/' . $news->image);
            }

            // Delete the news record from the database
            $news->delete();

            // Return a success response
            return response()->json(['message' => 'News deleted successfully!'], 200);
        } else {
            return response()->json(['message' => 'News not found.'], 404);
        }
    }



}
