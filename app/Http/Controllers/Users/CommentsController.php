<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function get() {
        $comments = Comment::all();
        return response()->json(CommentResource::collection($comments));
    }
}
