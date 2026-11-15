# Chapter 10 — AGENTS.md: The Behaviour Manual

> **What you will learn:** why the agent needs rules separate from the application's specification; the eleven rules that govern EduQuiz's build, and the specific failure each prevents; how the agent should report its work; and why the software industry is converging on exactly this pattern.

## 10.1 Specifying the worker, not the work

The three documents so far describe the *application*. None of them describes the *agent* — how it should work, what it must not touch, when it should stop and ask. That is the job of `AGENTS.md`.

The distinction matters because the commonest agent failures are not misunderstandings of the application. They are behaviours: doing more than asked, adding a package without asking, reorganising files nobody mentioned, guessing silently when instructions are ambiguous. You cannot prevent these by describing the application more precisely. You prevent them by telling the worker how to work.

`AGENTS.md` sits at the root of the repository, not in `docs/`, because that is where agentic tools look for it. GitHub Copilot's agent mode, Cline, and many of their competitors read a root-level `AGENTS.md` at the start of a session. It is the one document the agent consults without being told.

## 10.2 Context: pointing at the other documents

```markdown
You are building EduQuiz inside a prepared Docker environment. The requirements
are in `docs/PRD.md`, the system design in `docs/Architecture.md`, the visual
rules in `docs/design.md`, and the roadmap in `docs/TODO.md`. These documents
are the single source of truth.
```

The opening paragraph does two jobs. It tells the agent where everything is, and it establishes a hierarchy: when the agent's training says one thing and the documents say another, the documents win. "Single source of truth" licenses the agent to prefer your file over its prior habits.

## 10.3 The rules, and what each one prevents

EduQuiz's `AGENTS.md` contains eleven rules. Every one is there because an agent, left alone, tends to do the opposite.

**1. Do only the current task.** *Prevents: helpful expansion.* An agent asked to build a quiz form will often add a search box, a preview pane, or a bulk-delete button, because the applications it learned from had them. Each addition is code you did not ask for and must still review. The rule makes extra work a violation rather than a gift.

**2. No new dependencies.** *Prevents: package sprawl.* Agents install packages freely, because in their training data packages solved problems. The rule names the likeliest offenders — Spatie's permission package, Livewire, component libraries — because naming is more effective than a general prohibition.

**3. Blade and Tailwind only.** *Prevents: stack drift.* This repeats the stack decision from `Architecture.md` at the level of behaviour. Repetition across documents is deliberate: the agent may weight one file more than another in a given session.

**4. The schema is fixed.** *Prevents: silent schema changes.* An agent that needs a value the schema does not provide will add a column to get it — `is_active`, `slug`, `sort_order`. The rule forbids any table, column, or migration the task does not call for. This rule is the main reason every reader's database matches.

**5. Validate and authorize.** *Prevents: the happy-path implementation.* Agents tend to write the code that works when inputs are good and the user is honest. The rule makes validation and ownership checks mandatory on every action, and names `QuizPolicy` as the place for them.

**6. Small diffs.** *Prevents: drive-by refactoring.* While implementing a feature, an agent may "tidy up" nearby code — renaming variables, reformatting files, reorganising imports. Each change is harmless alone; together they bury the real change in noise and make review impossible.

**7. Never touch the environment.** *Prevents: environmental repairs.* When a command fails, an agent's instinct is to fix the configuration. In this workflow, the environment is known-good and identical for every reader (Chapter 5). If something fails, the fault is in the code, and the agent must not "fix" `docker-compose.yml` or `.env` to make it pass.

**8. Verify before finishing.** *Prevents: untested reports.* The agent must run migrations and load the affected pages, or run the tests, before claiming completion. A report of "done" means "done and checked."

**9. Report in a fixed format.** *Prevents: unreviewable summaries.* See §10.4.

**10. design.md describes the finished app.** *Prevents: premature features.* This rule was added after the validation build, for the reasons told in Chapter 9: parts of pages were built that depended on tables and routes not yet created.

**11. Ask instead of guessing.** *Prevents: silent interpretation.* When a task is ambiguous, or conflicts with a document, the agent must stop and ask. This is the most valuable rule in the file, and the validation build hit exactly the case it exists for. Task T3 originally asked for quiz counts on the dashboards — but quizzes did not exist until T4. A rule-following agent cannot both obey T3 and obey rule 4 (the schema is fixed). With rule 11, it stops and reports the conflict. Without it, it might invent a table to satisfy the task. **An agent that asks is the system working**, not failing.

## 10.4 The report: making review fast

Rule 9 fixes the format of every agent report:

```markdown
Report in a fixed format: files created/changed, commands run, and one
sentence telling a human how to verify in the browser (URL + what they
should see).
```

A report in this format, for task T8, looks like this:

```text
Files changed:
  app/Http/Controllers/QuizController.php   (added publish method)
  routes/web.php                            (added quizzes.publish route)
  resources/views/quizzes/show.blade.php    (publish button + badge)
Commands run:
  php artisan test                          (all passed)
Verify:
  Open /quizzes/1 as the lecturer, click Publish on an empty quiz — it should
  stay Draft with an error; add a question and click again — badge turns green.
```

This format exists to serve the review loop of Chapter 6. The file list is your scope check: compare it with `git status`, and any discrepancy is a flag. The commands tell you what the agent tested. The verify sentence turns the task's acceptance criterion into a concrete thirty-second browser check. Without a fixed format, agent reports drift into enthusiastic paragraphs that take longer to read than the code.

## 10.5 Commands: where they run

```markdown
All commands run inside the app container: `docker compose exec app php artisan …`.
Assets: the `node` service already runs `npm run dev`; do not restart it.
```

Chapter 5 warned that an agent might run `php artisan migrate` on your machine rather than inside the container. This section prevents it by giving the exact command form. Stating the obvious explicitly is a recurring theme in `AGENTS.md`. The agent does not know what is obvious in *your* project.

## 10.6 Writing rules that work

A few principles distilled from writing and testing this file:

- **Write rules as instructions, not aspirations.** "Do only the current task" works; "try to keep changes focused" does not.
- **Name the specific violations.** "No Spatie packages, no Livewire" outperforms "avoid unnecessary dependencies."
- **Keep the list short enough to obey.** Eleven rules fit in an agent's working attention. Fifty would dilute each one.
- **Add rules from evidence.** Rule 10 came from an observed failure. When your agent misbehaves in a way a rule could have prevented, add the rule — and commit it, so the fix applies to every future session.

## 10.7 Industry note: the convention is spreading

`AGENTS.md` is not this book's invention. It began as a convention among agentic tool makers and has spread rapidly, because every team that uses coding agents hits the same failures and arrives at the same remedy.

A striking sign appeared during the validation build. When `composer create-project` generated the Laravel 13 skeleton, it already contained a file of agent instructions pointing to **Laravel Boost**, the framework's own package of agent guidelines. Framework authors now ship behaviour manuals for AI agents alongside the framework itself.

This book does not install Boost: rule 2 forbids unrequested packages, and the companion repository was validated without it. But it is worth exploring once you finish EduQuiz. Framework-supplied guidelines teach the agent the *framework's* conventions; your `AGENTS.md` teaches it *your project's*. The two layers complement each other — and the fact that both exist is the best evidence that writing a behaviour manual is now a core development skill.

## 10.8 Checklist for your own AGENTS.md

- [ ] At the repository root, not in `docs/`
- [ ] Context paragraph naming every other document as the source of truth
- [ ] Rules covering scope, dependencies, stack, schema, validation, diff size, environment, verification, reporting, end-state, and asking
- [ ] Named examples of forbidden packages or patterns
- [ ] Exact command forms for your environment
- [ ] A fixed report format with a human verification sentence

One document remains. It is the one that turns the other four into a sequence of work.
