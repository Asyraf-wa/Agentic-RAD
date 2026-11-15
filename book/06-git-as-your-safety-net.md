# Chapter 6 — Git as Your Safety Net

> **What you will learn:** the Git operations this workflow runs on — commit, diff, restore, tag, switch; the checkpoint discipline that makes AI mistakes cheap; and how to read a diff well enough to review an agent's work. Git is not a chapter topic here out of tradition: it is the mechanism that makes the whole method safe.

## 6.1 Why Git is load-bearing in an agentic workflow

In a classical course, version control is taught as hygiene — a good habit, like backups. In an agentic workflow it is structural. Recall the loop every build chapter runs:

> give the agent **one task** → **verify** the result → **commit** → next task

The commit is not bookkeeping; it is the *ratchet*. Each verified task becomes a permanent floor beneath you. When — not if — an agent task goes wrong, the damage is confined to the one small, uncommitted change in front of you, and the response costs one command. An AI agent is a fast, tireless, occasionally overconfident collaborator; Git is what makes such a collaborator safe to employ.

Three specific guarantees:

- **Any single task can be discarded** the moment verification fails (`git restore .`).
- **Any chapter's end-state can be restored exactly** from the book's own tags, so no reader can be stranded by an unlucky generation.
- **Every change has an audit trail** — in an age of generated code, *the diff is the review*, and the history is the record of what was verified when.

## 6.2 The mental model in one paragraph

A Git **repository** is your project folder plus a history ledger (the hidden `.git` directory — you already have one; `git clone` in Chapter 5 created it). A **commit** is a snapshot of every file at one moment, with a message, stamped onto the ledger. A **diff** is the exact difference between two states, line by line. A **branch** is a named line of commits that moves forward as you commit — you made one called `my-build` in Chapter 5. A **tag** is a fixed bookmark on one commit (`chapter-13`) that never moves. That is the entire vocabulary this book uses; the merging and collaboration features that fill Git courses matter enormously in team development and not at all for our loop.

## 6.3 The five commands of the loop

```bash
git status                 # what has changed since the last snapshot?
git diff                   # show me those changes, line by line
git add -A                 # stage everything that changed…
git commit -m "T6: add questions and options"   # …and snapshot it
git restore .              # discard all uncommitted changes  ← the undo
```

Run `git status` now, in your `eduquiz` folder, after Chapter 5's setup. You will see `.env` is *not* listed even though it was created — the repository deliberately ignores it (secrets never enter history), one more pre-made decision you inherit from the environment.

One caution about `git restore .`: it restores files Git already knows about, but leaves *new* files the agent created. If a discarded task created new files, `git status` will still list them as untracked; remove them with `git clean -fd` (which deletes untracked files — check the list first with `git clean -nd`).

The habit to build before Part IV: **status → diff → verify → commit**, in that order, after every agent task. Most agentic tools show a diff view of their proposed edits before applying them — that is the same review, earlier. Use both.

## 6.4 Reading a diff: the reviewer's half-page

A diff shows removed lines prefixed `-`, added lines prefixed `+`, in context. Here is a fragment of a real one from the validation build behind this book — task T2, which added the role field to registration:

```diff
         $request->validate([
             'password' => ['required', 'confirmed', Rules\Password::defaults()],
+            'role' => ['required', 'in:lecturer,student'],
         ]);

         $user = User::create([
             'name' => $request->name,
             'email' => $request->email,
             'password' => Hash::make($request->password),
+            'role' => $request->role,
         ]);
```

Reviewing a diff is Chapter 2's three questions, applied mechanically:

1. **Scope** — are only the expected files present? (A task about registration has no business in `docker-compose.yml`.)
2. **Design** — do the changes match the documents? (That `in:lecturer,student` matches the ERD's enum exactly.)
3. **Excess** — is anything here beyond the task? (A helpful extra feature is a violation, not a gift — Chapter 10 makes this a rule.)

You do not need to understand every token. You need to notice what changed, where, and whether it was asked for. Five minutes per task, falling to two with practice.

## 6.5 Checkpoints: the tags that un-strand you

The companion repository carries a tag at the start and at every build-chapter boundary:

| Tag | The app at that moment |
| --- | --- |
| `chapter-start` | Laravel skeleton, Docker environment, the five documents; nothing built |
| `chapter-12` | Registration, login, roles, role-aware dashboards |
| `chapter-13` | Lecturer quiz CRUD |
| `chapter-14` | Questions and options |
| `chapter-15` | Publishing; students see published quizzes |
| `chapter-16` | Taking a quiz, one attempt enforced |
| `chapter-17` | Scoring, results, demo seeder |

Each chapter tag is the state produced and verified by this book's own validation build. Two uses:

**Recovery.** Your Chapter 14 session has gone sideways — the agent restructured something, verification fails, re-prompting is digging deeper. Stop. Run:

```bash
git restore .                          # discard the mess
git switch -c from-ch14 chapter-14     # a new branch on the book's verified code
```

…and continue into Chapter 15 on that branch, from known-good ground. This is not cheating; this is the system working. Your earlier work is untouched on `my-build`; `git switch my-build` returns to it at any time.

**Starting late.** A reader who wants only the deployment chapter can begin from `chapter-17` the same way. An instructor can start week 8's class with every student on an identical, correct codebase regardless of week 7's adventures — a sentence every teacher will reread.

After each of your own verified chapters, plant your own tag too:

```bash
git tag my-chapter-13
```

Your tags and the book's coexist; yours mark *your* verified states.

## 6.6 Publishing your history: GitHub

Your repository so far lives only on your machine. Pushing it to GitHub gives you an off-machine backup, a public home for the finished open educational tool (Chapter 19), and — if you claimed GitHub Education in Chapter 4 — you already have the account.

On GitHub, create an empty repository named `eduquiz` (no README — the project has one). Then, once, point your folder at it under the name `mine`, keeping `origin` for the book's repository:

```bash
git remote add mine https://github.com/YOUR-USERNAME/eduquiz.git
git push -u mine my-build
```

…and afterwards, a plain `git push` after each committed task. Git will walk you through authentication in the browser on first push. Keeping both remotes means `git fetch origin --tags` can still retrieve the book's checkpoint tags at any time.

> **When the AI gets it wrong — the meta-version.** Agents can run Git too, and `AGENTS.md` does not grant ours that privilege: the agent changes files; *you* commit. The commit is the moment of human sign-off — the boundary between "the machine proposed" and "I verified." Hand that to the machine and the audit trail records only that the agent approved itself. Some tools offer auto-commit conveniences; leave them off for this book.

## 6.7 The workbench is complete

Editor and agent (Chapter 4), identical running environment (Chapter 5), ratchet and safety net (this chapter). Verify the full set:

- [ ] `docker compose up` → welcome page at `localhost:8000`
- [ ] `git status` runs and shows a clean (or explicable) tree on branch `my-build`
- [ ] `git switch --detach chapter-12 && git switch my-build` round-trips without error — proof your safety net actually deploys
- [ ] Your agent answers a question in its panel

Nothing about EduQuiz has been built yet, and that is by design: every hour spent here was an hour bought back from every chapter that follows. Part III now turns to the five documents — the part of software that remains, unambiguously, yours.
