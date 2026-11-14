<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $lecturer = User::create([
            'name' => 'Dr Aminah Hassan',
            'email' => 'lecturer@example.com',
            'password' => Hash::make('password'),
            'role' => 'lecturer',
        ]);

        foreach (range(1, 5) as $n) {
            User::create([
                'name' => "Student {$n}",
                'email' => "student{$n}@example.com",
                'password' => Hash::make('password'),
                'role' => 'student',
            ]);
        }

        $quizzes = [
            'Web Fundamentals: HTML & CSS' => [
                ['What does HTML stand for?', ['HyperText Markup Language', 'Home Tool Markup Language', 'Hyperlinks and Text Markup Language', 'HighText Machine Language'], 0],
                ['Which tag creates a hyperlink?', ['<a>', '<link>', '<href>', '<url>'], 0],
                ['Which property changes text colour in CSS?', ['color', 'font-color', 'text-style', 'foreground'], 0],
                ['What does CSS stand for?', ['Cascading Style Sheets', 'Creative Style System', 'Computer Styled Sections', 'Coded Style Sheets'], 0],
                ['Which HTML element holds the page title shown in the browser tab?', ['<title>', '<head>', '<h1>', '<meta>'], 0],
            ],
            'Introduction to Databases' => [
                ['Which SQL statement retrieves data?', ['SELECT', 'GET', 'FETCH', 'RETRIEVE'], 0],
                ['A primary key must be…', ['unique for every row', 'a number', 'the first column', 'indexed manually'], 0],
                ['Which relationship does a foreign key create?', ['a link between two tables', 'a backup of a table', 'an index on a column', 'a copy of a row'], 0],
                ['What does DBMS stand for?', ['Database Management System', 'Data Backup Main Server', 'Database Mapping Service', 'Digital Base Management Suite'], 0],
                ['Which command removes a table entirely?', ['DROP TABLE', 'DELETE TABLE', 'REMOVE TABLE', 'TRUNCATE ROW'], 0],
            ],
        ];

        foreach ($quizzes as $title => $questions) {
            $quiz = $lecturer->quizzes()->create(['title' => $title, 'status' => 'published']);
            foreach ($questions as [$text, $options, $correctIndex]) {
                $question = $quiz->questions()->create(['question_text' => $text]);
                foreach ($options as $i => $optionText) {
                    $question->options()->create([
                        'option_text' => $optionText,
                        'is_correct' => $i === $correctIndex,
                    ]);
                }
            }
        }
    }
}
