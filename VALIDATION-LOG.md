# EduQuiz validation build log

Findings that must flow back into the book chapters and the five documents.

## Environment

- Current Laravel at build time: **13.42** (Architecture.md said 12 → update Architecture.md to "Laravel 13, exact version pinned in composer.lock").
- Laravel 13 novelty worth a concept box: model `Fillable` is now a PHP attribute (`#[Fillable([...])]`) instead of a `$fillable` property. Agents trained on older Laravel may write the old style — both work, but the generated skeleton uses attributes.
- Breeze 2.4 installs cleanly on Laravel 13 (`composer require laravel/breeze --dev`, `php artisan breeze:install blade`). Flagged risk resolved.
- Validation ran on MariaDB 10.11 (workspace constraint); book's Docker env uses MySQL 8.0 — schema is compatible, but final re-run should use the real compose stack.
- docker-compose.yml in this repo is a DRAFT (php:8.3-cli base lacks composer/extensions); needs a proper Dockerfile before the repo ships.

## Per-task findings

- **T1** — clean. Breeze ships 25 auth tests; `php artisan test` is a perfect verify step. Chapter 12 should tell readers to expect ~25 passing tests.
- **T2** — clean, with one trap: Breeze's `RegistrationTest` posts without a role and fails validation until the test gains `'role' => 'student'`. Fix TODO T2 verify line: "…roles stored correctly **and `php artisan test` still passes (the registration test needs the new field)**."
- **T3** — **task-ordering bug found**: T3 asks for quiz counts on dashboards, but the Quiz model only exists after T4. A rule-following agent must stop and ask (AGENTS.md rule 10). Fix TODO T3: "Role-aware dashboard: different welcome text and navigation per role; quiz counts are added in T4 (lecturer) and T9 (student)." This is also a good sidebar for the book: task ordering is part of the author's job, and the agent asking is the system working.

- **T4/T9/T10 ordering** — design.md describes finished pages, tasks build toward them: `withCount('questions')` breaks before T6; the student "Take quiz" button needs T10's route. Add one line to AGENTS.md or TODO.md intro: "design.md describes the END state; implement only what the current task needs." The minimal manage-page shell also moved into T5 so Chapter 13 ends green.
- **T11/T12 overlap** — scoring falls out naturally while implementing attempt submission; separating them is artificial. Merge: T11 = "submit, validate, score, one-attempt rule"; T12 = "score display on the student list + flash."
- **Stale Tailwind build (book-worthy gotcha)** — classes added after the last `npm run build` are missing from the CSS: the student cards rendered without the grid and the indigo button text sat white-on-white. In Docker the `node` service running `npm run dev` prevents this; anyone running without it must rebuild. Deserves a troubleshooting-appendix entry AND a warning box in Chapter 13 (first new view).
- **Screenshot technique** — app screenshots were captured headlessly via a temporary local-only `/dev-login/{id}?to=...` route (never committed) + `google-chrome --headless=new --screenshot`. Reusable for future editions.
- **Commit granularity** — this build committed per task-pair; the book's rule is one commit per task. Follow the book's rule when re-running for final screenshots.

## Verdict

Feasible end to end. 14/14 tasks completed against the five documents with no schema changes and no extra packages; 36 tests, 122 assertions passing; seeded demo working; all key pages captured. Remaining before the repo ships: proper Dockerfile for the `app` service, re-run from clean clone on the real compose stack, apply the TODO.md wording fixes above.

- **Laravel 13 ships agent guidelines in the skeleton** — new projects include a `CLAUDE.md` pointing at Laravel Boost (`composer require laravel/boost --dev`), the framework's own agent-guidance package. Not installed here (AGENTS.md rule 2; post-validation lock stability), but the book should cover it as a sidebar in Chapter 10: the industry is converging on the behaviour-manual pattern the book teaches.

## Post-build corrections (found while writing the book, 15 Nov 2026)

- **T9/T10 ordering** — T9 asked for a Take quiz button whose route arrives in T10 (Laravel refuses to render a page naming an undefined route). Button moved into T10. Same class of mistake as T5's Manage link.
- **Architecture.md authorization convention** — said "role checks via a simple `role` middleware"; the build used `QuizPolicy` plus a private guard in `AttemptController`. Document corrected to match the verified code.
- **PRD FR-6** — said cards show "Not attempted"; the build shows a Take quiz button. PRD corrected.
- **Docker environment** — `.env.example` defaults to SQLite in Laravel 13; `docker-compose.yml` now sets `DB_CONNECTION=mysql` for the app service. A real `docker/Dockerfile` replaced the draft. **Not yet run end to end on Docker Desktop**; the validation build ran on native PHP 8.3 + MariaDB 10.11.
- **Repository structure** — `main` holds the finished app; readers start from the `chapter-start` tag (skeleton + Docker + the five documents). History rebuilt so every chapter tag carries the documents.
