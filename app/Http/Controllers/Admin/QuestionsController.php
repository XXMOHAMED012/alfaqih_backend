<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionsController extends Controller
{
    public function index()
    {
        $questions = Question::all();
        return response()->json($questions);
    }

    public function show($id)
    {
        $question = Question::find($id);
        
        if (!$question) {
            return response()->json(['error' => 'Question not found'], 404);
        }
        
        return response()->json($question);
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string'
        ]);

        $question = new Question();
        $question->question = $request->input('question');
        $question->answer = $request->input('answer');
        $question->save();

        return response()->json($question, 201);
    }

    public function update(Request $request, $id)
    {
        $question = Question::find($id);

        if (!$question) {
            return response()->json(['error' => 'Question not found'], 404);
        }

        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string'
        ]);

        $question->question = $request->input('question');
        $question->answer = $request->input('answer');
        $question->save();

        return response()->json($question);
    }

    public function destroy($id)
    {
        $question = Question::find($id);

        if (!$question) {
            return response()->json(['error' => 'Question not found'], 404);
        }

        $question->delete();
        
        return response()->json(['message' => 'Question deleted'], 204);
    }
}
