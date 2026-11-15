# Agentic-RAD — EduQuiz

Companion repository for the book **_Rapid Web Application Development Using Agentic AI_** by Dr. Muhammad Asyraf Wahi Anuar (Faculty of Information Science, Universiti Teknologi MARA).

EduQuiz is a small quiz application built entirely by directing an AI coding agent through five planning documents. Lecturers create and publish multiple-choice quizzes; students take each quiz once and get an automatic score.

## The five documents

| File | Purpose |
| --- | --- |
| [`docs/PRD.md`](docs/PRD.md) | What we are building: users, roles, features, what is out of scope |
| [`docs/Architecture.md`](docs/Architecture.md) | How it is built: stack, ERD, routes, conventions |
| [`docs/design.md`](docs/design.md) | What it looks like: Tailwind palette, layout, pages |
| [`AGENTS.md`](AGENTS.md) | The behaviour manual the AI agent must follow |
| [`docs/TODO.md`](docs/TODO.md) | The roadmap: one task per agent run, each with a verify step |

## Following the book

The `main` branch holds the **finished** application. To build it yourself, start from the bare starting point on your own branch:

```bash
git clone https://github.com/Asyraf-wa/Agentic-RAD.git eduquiz
cd eduquiz
git switch -c my-build chapter-start
docker compose up
```

Chapter 5 of the book walks through the one-time setup that follows.

## Chapter checkpoints

Each build chapter of the book ends at a Git tag. Stuck, or did the agent go off the rails? Continue from the last known-good state on a new branch:

```bash
git restore .
git switch -c from-ch14 chapter-14
```

| Tag | Book chapter | App state |
| --- | --- | --- |
| `chapter-start` | Chapter 5 | Laravel skeleton, Docker environment, the five documents; nothing built |
| `chapter-12` | Authentication and roles | Register/login with lecturer or student role |
| `chapter-13` | Quiz CRUD | Lecturers create, edit, delete quizzes |
| `chapter-14` | Questions and options | Questions with 4 options, one correct |
| `chapter-15` | Publishing | Draft/published toggle; students see published quizzes |
| `chapter-16` | Taking a quiz | Students take a quiz once; auto-scored |
| `chapter-17` | Scoring and results | Score display, lecturer results page, demo data |

## Quick start (without Docker)

Requirements: PHP 8.3+, Composer, Node 20+, MySQL 8 (or MariaDB 10.11).

```bash
composer install
npm install && npm run build
cp .env.example .env && php artisan key:generate
# set DB_* values in .env, then:
php artisan migrate --seed
php artisan serve
```

In `.env`, set `DB_CONNECTION=mysql` (Laravel's template defaults to SQLite) and your database credentials.

Open http://localhost:8000 and log in with a demo account (password `password`):

- Lecturer: `lecturer@example.com`
- Students: `student1@example.com` … `student5@example.com`

> **With Docker:** `docker compose up`, then the setup commands in the book's Chapter 5. The compose file points Laravel at its MySQL container for you.

## Tests

```bash
php artisan test
```

`tests/Feature/AcceptanceTest.php.example` contains the acceptance checks used to validate the TODO tasks during the book's validation build. Copy it to `tests/Feature/AcceptanceTest.php` to run them.

## The book

The Markdown source of *Rapid Web Application Development Using Agentic AI* is in [`book/`](book/), with figures in `book/figures/`.

## Licence

To be confirmed alongside the book's open-licence decision.
