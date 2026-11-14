<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->role === 'lecturer') {
            $quizzes = $request->user()->quizzes()->latest()->get();

            return view('quizzes.index', ['quizzes' => $quizzes]);
        }

        $quizzes = Quiz::where('status', 'published')->get();

        return view('quizzes.student-index', ['quizzes' => $quizzes]);
    }

    public function create()
    {
        Gate::authorize('create', Quiz::class);

        return view('quizzes.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Quiz::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->quizzes()->create($validated);

        return redirect()->route('quizzes.index')->with('success', 'Quiz created.');
    }

    public function show(Quiz $quiz)
    {
        Gate::authorize('manage', $quiz);

        $quiz->load('questions.options');

        return view('quizzes.show', ['quiz' => $quiz]);
    }

    public function edit(Quiz $quiz)
    {
        Gate::authorize('manage', $quiz);

        return view('quizzes.edit', ['quiz' => $quiz]);
    }

    public function update(Request $request, Quiz $quiz)
    {
        Gate::authorize('manage', $quiz);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        $quiz->update($validated);

        return redirect()->route('quizzes.index')->with('success', 'Quiz updated.');
    }

    public function destroy(Quiz $quiz)
    {
        Gate::authorize('manage', $quiz);

        $quiz->delete();

        return redirect()->route('quizzes.index')->with('success', 'Quiz deleted.');
    }
}
