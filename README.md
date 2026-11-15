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

## Chapter checkpoints

Each build chapter of the book ends at a Git tag. Stuck, or did the agent go off the rails? Jump to the last known-good state:

```bash
git checkout chapter-14
```

| Tag | Book chapter | App state |
| --- | --- | --- |
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

Open http://localhost:8000 and log in with a demo account (password `password`):

- Lecturer: `lecturer@example.com`
- Students: `student1@example.com` … `student5@example.com`

> **Docker:** `docker-compose.yml` is a working draft; a finished Dockerfile and the `docker compose up` workflow arrive with the book's Chapter 5.

## Tests

```bash
php artisan test
```

`tests/Feature/AcceptanceTest.php.example` contains the acceptance checks used to validate every TODO task during the book's validation build.

## Licence

To be confirmed alongside the book's open-licence decision.
