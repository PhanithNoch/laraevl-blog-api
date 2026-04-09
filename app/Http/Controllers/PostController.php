<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    // crud operations [CREATE,READ,UPDATE,DELETE]

    // get all 
    public function index()
    {
        // findAll 
        $posts = Post::all();
        return response()->json([
            'message' => "success",
            'data' => $posts
        ]);
    }

    // insert 
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $data['image_url'] = $path;

        }

        $post = Post::create($data);


        return response()->json([
            'message' => "success",
            'data' => $post
        ]);
    }

    // update 
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        $data = $request->all(); // request client 
        //image 

        if ($request->hasFile('image')) {
            // 1. Delete the old image if it exists
            if ($post->image_url) {
                $oldPath = $post->image_url;
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $request->file('image')->store('images', 'public'); // store in public folder in project 
            //old image? keep or delete? if you wanna delete how?
            $data['image_url'] = $path;


        }
        $post->update($data);
        //return respone 
        return response()->json([
            'message' => "post created",
            'success' => true,
            'data' => $post
        ], 200);
    }

    // delete 
    public function destroy($id)
    {
        $post = Post::find($id); // where 
        if (!$post) {
            return response()->json([
                'message' => "failed to delete",
                'success' => false
            ], 404);
        }

        if ($post->image_url) {
            // 1. Delete the old image if it exists
            $oldPath = $post->image_url;
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

        }
        $post->delete();
        return response()->json([
            'message' => "post deleted",
            'success' => true
        ], 200);
    }
}


// Follow my lesson build blog api 
//1. Update post with image 
//2. Delete post 