# Chapter 11 — TODO.md: The Roadmap

> **What you will learn:** how to slice a whole application into tasks an agent can complete and you can verify in minutes; the anatomy of a good task and its verify line; how to order tasks so every chapter ends with a working application; and the three ordering mistakes the validation build caught in this book's own roadmap.

## 11.1 The unit of work

Everything in the previous four chapters sets up this one. The PRD, Architecture, design, and behaviour manual describe *what* and *how*. `TODO.md` decides *in what pieces, in what order* — and the size of those pieces determines whether agentic development feels like magic or like debugging someone else's homework.

The governing rule, stated at the top of EduQuiz's roadmap:

> One task = one agent run = one commit.

Each task is handed to the agent alone. Its output is verified alone. If it passes, it is committed alone. If it fails, it is discarded alone (`git restore .`), at the cost of one task's worth of work. The task is the unit of risk.

## 11.2 Anatomy of a task

Here is task T6 from EduQuiz's roadmap:

```markdown
- [ ] **T6** `Question` and `Option` models + migrations (per ERD);
  add-question form on the quiz manage page (text, 4 options, radio marks the
  correct one) with validation. *Verify: add a question; exactly 4 options
  saved, one correct.*
```

Every task has four parts:

1. **An identifier** — `T6`. You will type it into the agent's chat ("Complete task T6"), refer to it in commit messages, and use it in conversation. Short and stable.
2. **The work** — models, migrations, a form, with references *back* to the documents ("per ERD") rather than repeating them. The task points at the specification; it does not restate it.
3. **The scope boundary** — implicit in what is listed. T6 adds questions; it does not list them on the page (that is T7) or delete them (also T7).
4. **The verify line** — a check a human can perform in under a minute, with an unambiguous pass or fail. *Exactly 4 options saved, one correct* can be confirmed in the browser or the database.

The verify line is the task's acceptance criterion, and it is how this book keeps the promise of Chapter 1. Two readers' agents may write T6 in completely different code. Both readers' applications pass the same check. That is what "same outcome, not identical code" means operationally.

## 11.3 How big is a task?

A task is the right size when three things are true:

- **It produces a visible change.** Something new appears in the browser, or a check in the verify line now passes. A task that only "prepares" — creates a model nobody uses yet — cannot be verified and should be merged into the task that uses it.
- **Its diff fits in one review.** As a rough guide, a task should change between two and eight files.
- **It maps to one or two numbered requirements.** T6 implements FR-4. T8 implements FR-5. If a task touches four requirements, split it.

The largest task in EduQuiz, T6, creates two models, two migrations, a controller, a route, and an expanded view — about as big as a task should get. Scaffolding tasks such as T1 (installing Breeze) are the one exception: they generate dozens of files, and Chapter 12 shows how to review them differently.

## 11.4 Ordering: every chapter ends green

Tasks are grouped by chapter, and the grouping follows one rule: **at the end of every chapter, the application runs and something new works.** The chapter boundaries in EduQuiz's roadmap:

| Chapter | Tasks | What works at the end |
| --- | --- | --- |
| 12 | T1–T3 | Register, log in, see a role-specific dashboard |
| 13 | T4–T5 | Lecturer creates, edits, deletes quizzes |
| 14 | T6–T7 | Questions with options added and listed |
| 15 | T8–T9 | Publishing; students see published quizzes |
| 16 | T10–T11 | Student takes a quiz; score stored; one attempt enforced |
| 17 | T12–T14 | Score shown; lecturer results; demo data |

Each boundary is a Git tag in the companion repository (Chapter 6), so each "green" state is also a recovery point.

Within a chapter, order tasks by dependency: the data must exist before a page lists it, a page must exist before a button links to it. This sounds obvious. The validation build showed it is not.

## 11.5 Three ordering mistakes the validation build caught

The original roadmap — the one drafted for this book before any code ran — contained three ordering errors. All three looked fine on paper and failed in practice.

**Mistake 1: a task asked for data that did not exist yet.** Task T3 originally read: *"Role-aware dashboard: lecturer sees quiz count + manage link."* But the `quizzes` table is created in T4. An agent obeying T3 must either invent a quizzes table (violating rule 4) or stop and ask (rule 11). The fix: T3 now asks only for role-specific welcome text, and counts arrive with the tasks that create the data.

**Mistake 2: a chapter ended with a broken link.** Task T5 built the quiz list, with a *Manage* link on each quiz — but the manage page itself was not scheduled until T6, in the next chapter. Chapter 13 would have ended with a link that crashed. The fix: T5 now includes a minimal manage page (title and status badge), which T6 fills in. The same mistake appeared again in T9, whose *Take quiz* button pointed at a page not created until T10; the button now arrives together with its page.

**Mistake 3: two tasks that could not really be separated.** T11 handled quiz submission; T12 handled scoring. During the build, it proved impossible to store an attempt without storing its score — the `score` column is required. The fix: scoring moved into T11, and T12 became purely about *showing* the score to the student.

All three fixes are in the roadmap printed in Appendix D. The general lesson is worth more than the specifics: **ordering errors are invisible until something executes the plan.** If you can, dry-run your own roadmap — even mentally, task by task, asking "what does this task need that does not exist yet?" — before handing it to an agent.

## 11.6 Verify lines that actually verify

A verify line must be something the human checks, not something the agent asserts. Compare:

| Weak | Strong |
| --- | --- |
| *Verify: publishing works.* | *Verify: empty quiz won't publish; badge flips between draft and published.* |
| *Verify: students can take quizzes.* | *Verify: student opens the page; lecturer gets 403.* |
| *Verify: seeding is done.* | *Verify: fresh `migrate --seed` yields a working demo; lecturer login shows 2 quizzes.* |

The strong versions name an action, an observable result, and — wherever possible — a *negative* case: something that must **not** happen. Negative checks catch the happy-path implementations of Chapter 10's rule 5. An agent that forgot authorization will pass "student opens the page" and fail "lecturer gets 403."

> **Concept box: verify lines become tests.** During the validation build, the verify lines of tasks T3 to T13 each became an automated feature test. Together with Breeze's own 25 tests, that made the 36 tests the build finished with. The companion repository ships them as `tests/Feature/AcceptanceTest.php.example`. You do not need to write tests to follow this book; the manual checks suffice. But if you want an agent to verify its own work rigorously, telling it to "write a feature test for the verify line, then make it pass" is one of the most effective instructions available.

## 11.7 The checkbox and the commit

Each task begins as `- [ ]` and becomes `- [x]` when verified. Update the box in the same commit as the work:

```bash
git add -A
git commit -m "T6: questions and options with add form"
```

The roadmap thus records its own progress in Git: anyone opening the repository at any commit can see which tasks were done at that moment. It is a small habit with a large payoff in a classroom, where an instructor can check a student's progress by opening one file.

## 11.8 Checklist for your own TODO.md

- [ ] The one-task-one-commit rule stated at the top
- [ ] The note that `design.md` describes the finished application
- [ ] Tasks grouped by milestone, each milestone ending with a working app
- [ ] Each task: identifier, work referencing the documents, implicit scope, verify line
- [ ] Verify lines with an action, an observable result, and a negative case
- [ ] Dry-run: for each task, everything it needs is created by an earlier task

## 11.9 Part III complete

You now hold five documents, about seven pages in total, that describe an application completely — its purpose, structure, appearance, the behaviour of the worker who will build it, and the order of work. Writing them took longer than you might expect and much less than writing the application would.

Part IV is where they pay off. In the next chapter you open your agent, type a single sentence — *"Read AGENTS.md and the documents in docs/. Complete task T1."* — and watch the first piece of EduQuiz appear.
