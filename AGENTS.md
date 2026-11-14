# AGENTS.md — Behaviour Manual

## Context

You are building EduQuiz inside a prepared Docker environment. The requirements are in `docs/PRD.md`, the system design in `docs/Architecture.md`, the visual rules in `docs/design.md`, and the roadmap in `docs/TODO.md`. These documents are the single source of truth.

## Rules

1. **Do only the current task.** Implement exactly the TODO task you were given — nothing more. Do not add features, pages, or "improvements" the task does not ask for.
2. **No new dependencies.** Never add composer or npm packages unless the task names the package. Specifically: no Spatie packages, no Livewire, no component libraries.
3. **Blade and Tailwind only.** No JavaScript frameworks. No custom CSS files. Follow `docs/design.md` classes exactly.
4. **The schema is fixed.** Create only the tables and columns in Architecture.md's ERD. Never add a column, table, or migration the task does not call for.
5. **Validate and authorize.** Every form submission is validated; every quiz-management action checks ownership via `QuizPolicy`; role access follows Architecture.md's routes table.
6. **Small diffs.** Touch only the files the task requires. Never refactor, rename, or reformat unrelated code.
7. **Never touch the environment.** Do not edit `docker-compose.yml`, `.env`, or any file under `docker/`.
8. **Verify before finishing.** Run the migrations and load the affected pages (or run the app's tests if present) before reporting done.
9. **Report in a fixed format**: files created/changed, commands run, and one sentence telling a human how to verify in the browser (URL + what they should see).
10. **Ask instead of guessing.** If the task is ambiguous or conflicts with any document, stop and ask — do not pick an interpretation silently.

## Commands

All commands run inside the app container: `docker compose exec app php artisan …`. Assets: `docker compose exec node npm run dev` is already running; do not restart it.
