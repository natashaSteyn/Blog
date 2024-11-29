
<?php

namespace App\Http\Controllers;
use App\Models\User;

use App\Models\Comments;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index() {
        // return view('posts.index');
    }

    public function save(Request $request, $id) {

        $request->validate([
            'content' => 'required|string',
        ]);

        Comments::create([
            'user_id' => auth()->user()->id,     
            'post_id' => $id,
            'content' => $request->content
        ]);

        return response()->json([
            'message' => 'Comment made...'
        ]);
    }

    public function update(Request $request, $id) {

        $validatedData = $request->validate([
            'content' => 'required|string',
        ]);
    
        $comment = Comments::find($id);

        $ownerID = $comment->user_id;

        if ($ownerID != auth()->user()->id) {
            return response()->json([
                'message' => 'You are not allowed to update this comment'
            ], 403);
        }
    
        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found'
            ], 404);
        }
    
        $comment->content = $request->content;
    
        $comment->save();
    
        return response()->json([
            'message' => 'Comment updated successfully',
            'comment' => $comment
        ]);
    }

    public function destroy($id) {

        $comment = Comments::find($id);

        $ownerID = $comment->user_id;

        if ($ownerID != auth()->user()->id) {
            return response()->json([
                'message' => 'You are not allowed to delete this comment'
            ], 403);
        }
    
        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found'
            ], 404);
        }
    
        $comment->delete();
    
        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }

    public function getComments($id) {
        return response()->json([
            'comments' => Comments::where('post_id', $id)->get()
        ]);
    }
}
