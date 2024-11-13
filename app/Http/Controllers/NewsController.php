<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
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


}
