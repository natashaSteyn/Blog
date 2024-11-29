<?php

namespace App\Http\Controllers;
use App\Models\User;

use App\Models\Posts;
use Illuminate\Http\Request;

class PostsController extends Controller
{
 

	public function index(){
		// dd('Reached index');

		$data = Posts::get();
		return response()->json($data);

	}

	public function show(Request $request) {

        $search = $request->search;

        $posts = Posts::when($search, function ($query, $search) {
            $query->where('title', 'LIKE', "%{$search}%")->orWhere('content', 'LIKE', "%{$search}%");
        })->get();

        return response()->json([
            'posts' => $posts
        ]);
    }

	public function getOne($id) {
        return response()->json([
            'post' => Posts::find($id)
        ]);
    }

	public function store(Request $request) {

        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        Posts::create([
            'title' => $request->title,
            'content' => $request->content, 
            'user_id' => auth()->user()->id       
        ]);

		return response()->json(['message' => 'Record succesfully creted']);

    }

    public function update(Request $request, $id) {

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
    
        $post = Posts::find($id);

        $ownerID = $post->user_id;

        if ($ownerID != auth()->user()->id) {
            return response()->json([
                'message' => 'You are not allowed to update this post'
            ], 403);
        }
    
        if (!$post) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        }
    
        $post->title = $request->title;
        $post->content = $request->content;
    
        $post->save();
    
        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post
        ]);
    }

    public function destroy($id) {

        $post = Posts::find($id);

        $ownerID = $post->user_id;

        if ($ownerID != auth()->user()->id) {
            return response()->json([
                'message' => 'You are not allowed to delete this post'
            ], 403);
        }
    
        if (!$post) {
            return response()->json([
                'message' => 'Post not found'
            ], 404);
        }
    
        $post->delete();
    
        return response()->json([
            'message' => 'Post deleted successfully'
        ]);
    }

  

   
}