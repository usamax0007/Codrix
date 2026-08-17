<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'task_id' => 'required|exists:tasks,id',
                'comment' => 'required|string',
            ]);

            $comment = Comment::create([
                'task_id' => $request->task_id,
                'user_id' => Auth::id(),
                'comment' => $request->comment,
            ]);

            $comment->load('user');

            return response()->json([
                'success' => true,
                'comment' => $comment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}