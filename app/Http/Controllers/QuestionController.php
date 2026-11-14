<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class QuestionController extends Controller
{
    public function store(Request $request, Quiz $quiz)
    {
        Gate::authorize('manage', $quiz);

        $validated = $request->validate([
            'question_text' => ['required', 'string'],
            'options' => ['required', 'array', 'size:4'],
            'options.*' => ['required', 'string', 'max:255'],
            'correct' => ['required', 'integer', 'between:0,3'],
        ]);

        DB::transaction(function () use ($quiz, $validated) {
            $question = $quiz->questions()->create([
                'question_text' => $validated['question_text'],
            ]);

            foreach ($validated['options'] as $index => $text) {
                $question->options()->create([
                    'option_text' => $text,
                    'is_correct' => $index === (int) $validated['correct'],
                ]);
            }
        });

        return redirect()->route('quizzes.show', $quiz)->with('success', 'Question added.');
    }

    public function destroy(Question $question)
    {
        Gate::authorize('manage', $question->quiz);

        $quiz = $question->quiz;
        $question->delete();

        return redirect()->route('quizzes.show', $quiz)->with('success', 'Question deleted.');
    }
}
