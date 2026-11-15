# Chapter 2 — How to Read Laravel

> **What you will learn:** the MVC shape of a Laravel application; how one browser request travels through route, controller, model, and view; what migrations and Eloquent relationships are, using EduQuiz's own schema; and the handful of `artisan` commands you will watch your agent run.

## 2.1 Reading, not writing

This chapter has an unusual goal for a framework introduction: it does **not** try to teach you to write Laravel. Your AI agent will write it. What you must be able to do is *read* what the agent wrote — well enough to answer, in a minute or two, the only three questions that matter at review time:

1. Did it touch the files this task should touch, and only those?
2. Does the data flow match the design in `Architecture.md`?
3. Is anything here doing more than the task asked?

Reading fluency in a framework is a much smaller skill than writing fluency, the same way you can referee an essay in a language you would struggle to compose in. One chapter is enough.

## 2.2 The shape: Model, View, Controller

Laravel is an MVC framework. Three kinds of files, three jobs:

- **Models** (`app/Models/`) — one class per database concept. EduQuiz will have `Quiz`, `Question`, `Option`, `Attempt`, plus the `User` Laravel ships with. A model knows how to fetch and store rows, and what it is related to.
- **Views** (`resources/views/`) — Blade templates: HTML with small PHP islands, named `something.blade.php`. They decide what the user sees.
- **Controllers** (`app/Http/Controllers/`) — the coordinators. A controller method receives a request, asks models for data, applies rules, and hands the result to a view.

And one map that ties them together: **routes** (`routes/web.php`), which say *this URL, this method, goes to this controller*.

## 2.3 One request, end to end

Take the page you saw in Chapter 1 — the lecturer's quiz list at `/quizzes`. When a browser asks for it:

```text
Browser ── GET /quizzes ──▶ routes/web.php
                               │  "GET /quizzes → QuizController@index"
                               ▼
                         QuizController::index()
                               │  asks the model
                               ▼
                         Quiz::where(...)->get()   ── SQL ──▶ MySQL
                               │  rows come back as Quiz objects
                               ▼
                         view('quizzes.index', [...])
                               │  Blade fills in the HTML
                               ▼
Browser ◀── finished HTML ─────┘
```

Every page in EduQuiz — every page in most Laravel applications — is a variation of this one picture. When you review the agent's work, you are checking that each station on the line exists and does only its own job: the route points where `Architecture.md` says, the controller contains the rules (and the views do not), the model matches the schema.

Here is the actual `index` method your agent will produce in Chapter 15, from this book's validation build:

```php
public function index(Request $request)
{
    if ($request->user()->role === 'lecturer') {
        $quizzes = $request->user()->quizzes()->withCount('questions')->latest()->get();

        return view('quizzes.index', ['quizzes' => $quizzes]);
    }

    $quizzes = Quiz::where('status', 'published')->withCount('questions')->get();

    return view('quizzes.student-index', ['quizzes' => $quizzes]);
}
```

Read it with the three questions in mind. Lecturers get *their own* quizzes; students get *published* ones; each role gets its own view. That `->withCount('questions')` asks the database to count each quiz's questions on the way through. You could not have typed this on day one — but you can already tell whether it honours the design. That is the skill.

## 2.4 Migrations: the schema as code

Laravel never has you click around in a database tool to create tables. Tables are created by **migrations** — small PHP files in `database/migrations/`, each describing one change. Here is the heart of the one your agent will write for quizzes:

```php
Schema::create('quizzes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->enum('status', ['draft', 'published'])->default('draft');
    $table->timestamps();
});
```

Line by line: an auto-incrementing `id`; a `user_id` that must point at a real user and takes the quiz down with the user if the user is deleted (`cascadeOnDelete`); a text `title`; a `status` that can only be `draft` or `published`, starting as `draft`; and Laravel's standard `created_at`/`updated_at` pair.

Two things make migrations central to this book's method. They are **repeatable**: `php artisan migrate` builds the same schema on every machine, which is half of reproducibility. And they are **reviewable**: when the agent finishes a task, the migration is where you check the schema against the ERD in `Architecture.md`, column by column. `AGENTS.md` will forbid the agent from inventing columns — the migration file is where you catch it if it tries.

## 2.5 Eloquent relationships, using EduQuiz's own schema

Eloquent is Laravel's ORM — the layer that turns database rows into PHP objects. Its most important idea for your reviewing eye is the **relationship method**. EduQuiz's schema is a chain of one-to-many links:

> A **User** creates many **Quizzes** · a **Quiz** contains many **Questions** · a **Question** has four **Options** · a **Quiz** receives many **Attempts**, each made by one **User**.

In code, each link is a small, named method on the model:

```php
class Quiz extends Model
{
    public function user(): BelongsTo      { return $this->belongsTo(User::class); }
    public function questions(): HasMany   { return $this->hasMany(Question::class); }
    public function attempts(): HasMany    { return $this->hasMany(Attempt::class); }
}
```

Read `hasMany(Question::class)` as "a quiz *has many* questions"; read `belongsTo(User::class)` as "a quiz *belongs to* a user." Once defined, the rest of the application traverses the chain in plain language: `$quiz->questions`, `$question->options`, `$request->user()->quizzes()`. When you later read a controller line like

```php
$quiz->questions()->create(['question_text' => $validated['question_text']]);
```

you can decode it precisely: *create a question that belongs to this quiz.* The relationship methods are the vocabulary of the whole codebase; learn these two verbs and most lines in EduQuiz become legible.

> **Concept box: `#[Fillable]` — a Laravel 13 novelty.** Mass assignment is when a model is filled from an array of input (`Quiz::create([...])`). As a safety rule, a model must declare which fields may be filled that way. Older tutorials — and many AI agents trained on them — show a `protected $fillable = [...]` property. Laravel 13's skeleton instead uses a PHP attribute on the class: `#[Fillable(['title', 'status'])]`. Both work; the companion code uses the attribute. If your agent produces the older property form, it is not wrong — merely trained on yesterday.

## 2.6 The artisan commands you will see

`artisan` is Laravel's command-line toolbox. You will rarely type these yourself, but you will *watch the agent run them*, and you should know what you are watching:

| Command | What it does |
| --- | --- |
| `php artisan serve` | Runs the development web server |
| `php artisan migrate` | Applies any migrations not yet run |
| `php artisan migrate:fresh --seed` | Wipes the database, rebuilds it, fills in demo data |
| `php artisan make:model Quiz -m` | Generates a model plus its migration |
| `php artisan make:controller QuizController` | Generates a controller |
| `php artisan make:policy QuizPolicy` | Generates an authorization policy |
| `php artisan test` | Runs the automated test suite |
| `php artisan route:list` | Prints every route — useful when reviewing |

The last two deserve a star. `php artisan test` is the backbone of this book's verification steps — the validation build behind these pages finished with thirty-six tests passing, and several chapters use the test count itself as the checkpoint. `route:list` is the quickest way to compare what exists against the routes table in `Architecture.md`.

## 2.7 Where things live: the folder map

You will navigate the project tree constantly while reviewing. Eight locations cover nearly everything in this book:

```text
eduquiz/
├── app/Http/Controllers/   ← coordinators (QuizController, …)
├── app/Models/             ← Quiz, Question, Option, Attempt, User
├── app/Policies/           ← who may do what (QuizPolicy)
├── database/migrations/    ← the schema, as code
├── database/seeders/       ← demo data (Chapter 17)
├── resources/views/        ← Blade templates
├── routes/web.php          ← the URL map
└── docs/                   ← the five documents directing it all
```

If the agent reports changing a file outside these, that is itself a review flag: Chapter 10's behaviour manual tells it to touch only what a task requires.

## 2.8 What you can now do

You can trace a request from URL to HTML; read a migration against an ERD; decode `hasMany` and `belongsTo`; recognise the artisan commands in an agent's report; and find any file the agent names. That is a working reviewer's literacy — and reviewing is the job. The visual half of the application is next.
