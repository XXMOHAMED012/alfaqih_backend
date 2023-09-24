<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function index()
    {
        $comments = Comment::all();
        return response()->json(CommentResource::collection($comments));
    }

    public function show($id)
    {
        $comment = Comment::find($id);
        
        if (!$comment) {
            return response()->json(['error' => 'Comment not found'], 404);
        }
        
        return response()->json(new CommentResource($comment));
    }

    public function store(Request $request)
    {
        $comment = new Comment();
        $comment->name = $request->input('name');
        $comment->car = $request->input('car');
        $comment->rate = $request->input('rate');
        $comment->stars = $request->input('stars');
        $comment->save();

        return response()->json(new CommentResource($comment), 201);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['error' => 'Comment not found'], 404);
        }

        $comment->name = $request->input('name');
        $comment->car = $request->input('car');
        $comment->rate = $request->input('rate');
        $comment->stars = $request->input('stars');
        $comment->save();

        return response()->json(new CommentResource($comment));
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['error' => 'Comment not found'], 404);
        }

        $comment->delete();
        
        return response()->json(['message' => 'Comment deleted'], 204);
    }
}
