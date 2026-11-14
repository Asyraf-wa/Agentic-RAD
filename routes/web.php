<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('quizzes', App\Http\Controllers\QuizController::class);
    Route::patch('/quizzes/{quiz}/publish', [App\Http\Controllers\QuizController::class, 'publish'])->name('quizzes.publish');
    Route::get('/quizzes/{quiz}/take', [App\Http\Controllers\AttemptController::class, 'take'])->name('quizzes.take');
    Route::post('/quizzes/{quiz}/attempts', [App\Http\Controllers\AttemptController::class, 'store'])->name('attempts.store');
    Route::post('/quizzes/{quiz}/questions', [App\Http\Controllers\QuestionController::class, 'store'])->name('questions.store');
    Route::delete('/questions/{question}', [App\Http\Controllers\QuestionController::class, 'destroy'])->name('questions.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
