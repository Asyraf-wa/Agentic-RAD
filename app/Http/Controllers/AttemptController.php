<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class AttemptController extends Controller
{
    private function guardTakeable(Request $request, Quiz $quiz): void
    {
        abort_unless($request->user()->role === 'student', 403);
        abort_unless($quiz->status === 'published', 403);
        abort_if(
            $quiz->attempts()->where('user_id', $request->user()->id)->exists(),
            403,
            'You have already taken this quiz.'
        );
    }

    public function take(Request $request, Quiz $quiz)
    {
        $this->guardTakeable($request, $quiz);

        $quiz->load('questions.options');

        return view('quizzes.take', ['quiz' => $quiz]);
    }

    public function store(Request $request, Quiz $quiz)
    {
        $this->guardTakeable($request, $quiz);

        $quiz->load('questions.options');

        $rules = ['answers' => ['required', 'array']];
        foreach ($quiz->questions as $question) {
            $rules["answers.{$question->id}"] = [
                'required',
                'integer',
                'in:'.$question->options->pluck('id')->implode(','),
            ];
        }

        $validated = $request->validate($rules, [
            'answers.*.required' => 'Please answer every question.',
        ]);

        $score = 0;
        foreach ($quiz->questions as $question) {
            $chosen = (int) $validated['answers'][$question->id];
            $correct = $question->options->firstWhere('is_correct', true);
            if ($correct && $chosen === $correct->id) {
                $score++;
            }
        }

        $quiz->attempts()->create([
            'user_id' => $request->user()->id,
            'score' => $score,
            'submitted_at' => now(),
        ]);

        return redirect()->route('quizzes.index')
            ->with('success', "You scored {$score}/{$quiz->questions->count()} on {$quiz->title}");
    }
}
