# Chapter 18 — Seeding and Polish

> **What you will learn:** how to make demo data believable; how to audit the finished interface against `design.md` and direct the agent to fix drift; how to give the application its own identity; and how to write a polish task that stays small.

## 18.1 From working to presentable

At the end of Chapter 17, EduQuiz works. Every requirement passes its check. But "works" and "ready to show a class" are different standards. The seeded students are called *Student 1* to *Student 5*. The navigation bar still shows Laravel's logo. A few pages may have drifted from the design specification in ways no verify line caught.

This chapter closes that gap. Polish work is where agentic development tempts you hardest to abandon the method — "just tidy everything up" is an easy sentence to type — so the chapter also shows how to keep polish small and reviewable.

## 18.2 Believable demo data

A demonstration persuades when it looks like a real class. Three improvements to the seeder, each a small task you can give the agent:

**Realistic names.** Replace *Student 1* with names your audience recognises. For a Malaysian classroom, a mix such as *Nur Aisyah*, *Muhammad Hakim*, *Tan Wei Ling*, *Priya Ramasamy*, and *Daniel Wong* immediately makes the results table feel real. The lecturer account in the companion repository is seeded with the author's name; change it to yours.

**Your own subject.** The seeded quizzes cover web fundamentals and databases. Replace the question arrays with content from your own course. Because the seeder stores each question as a plain array — text, four options, correct index — this is editing, not programming.

**Some history.** A results page with no attempts demonstrates nothing. Seed a few attempts with varied scores so the lecturer's results page is populated from the first login.

Write these as a task in `TODO.md`, in the same format as the others:

```markdown
- [ ] **T15** Update DatabaseSeeder: realistic student names (5), lecturer
  named after the course owner, and 3 seeded attempts on quiz 1 with scores
  2/5, 4/5, 5/5. *Verify: fresh `migrate --seed`; lecturer's results page for
  quiz 1 shows 3 rows with those scores.*
```

Then run it through the usual loop. Adding the task to `TODO.md` before prompting keeps the record honest: the roadmap shows every change the application received, including the cosmetic ones.

## 18.3 The design audit

Part IV verified *behaviour*. It did not systematically verify *appearance*. Now is the time, and it is a job you do yourself, with `design.md` open beside the browser.

Go through each page in the specification's Pages table and check three things:

1. **Palette.** Every button, badge, and card uses the exact classes in `design.md`'s table. Inspect elements in the browser's developer tools; compare strings.
2. **Layout.** Every page follows the heading-row pattern (title left, primary action right) inside the content container.
3. **Content.** Every element listed for the page is present, including empty states.

Record each discrepancy as you find it. Auditing the validation build's finished app this way found the following:

| Page | Discrepancy | Source |
| --- | --- | --- |
| All pages | Navbar shows Laravel's logo, not "EduQuiz" | Breeze default, never changed |
| Lecturer dashboard | No quiz count or link to the quiz list | T3 deferred counts; no later task added them |
| Student dashboard | No count of available quizzes | Same as above |
| Lecturer quiz list | *Results* link present, not in `design.md` | Added in T13 for navigation |

The second and third rows are instructive. Chapter 11 moved the quiz counts out of T3 because quizzes did not exist yet — and then no later task brought them back. Deferring work is correct; forgetting deferred work is the risk. **When you defer part of a task, add a task for it immediately**, even if it sits at the end of the roadmap.

The fourth row runs the other way: the application has something the design lacks. A *Results* link on each quiz is clearly useful, so the right fix is to update `design.md`, not to remove the link. The specification follows good decisions; it is not a cage.

## 18.4 Fixing drift with small tasks

Turn the audit into tasks. Resist the single sweeping prompt ("fix everything in the audit"): it produces a diff spanning a dozen files that you cannot review properly. Instead:

```markdown
- [ ] **T16** Navbar: replace the Laravel logo with the text "EduQuiz"
  (text-lg font-semibold text-gray-900). *Verify: every page shows "EduQuiz"
  top left; no Laravel logo anywhere.*
- [ ] **T17** Dashboards: lecturer sees count of own quizzes + link to quiz
  list; student sees count of published quizzes + link to quiz list.
  *Verify: seeded lecturer sees "2 quizzes"; seeded student sees "2 available".*
```

Each task is one concern, a handful of files, and a check you can do in thirty seconds. T17 is the deferred dashboard work from T3, finally returned — now possible, because quizzes exist.

For the *Results* link, edit `design.md` yourself: it is a one-line change to the Quiz list row, and committing it keeps the specification accurate for whoever reads it next.

## 18.5 Identity

Two touches make the application feel like yours rather than a framework demonstration:

- **Application name.** The `APP_NAME` value in `.env` controls the browser tab title. This is one of the rare cases where you edit `.env` — by hand, not through the agent, because rule 7 forbids it from touching the environment.
- **Welcome page.** The root URL still shows Laravel's welcome page. A small task can replace it with a simple landing page — the app's name, one sentence about what it does, and *Log in* and *Register* buttons in the house style — or simply redirect visitors to the login page.

## 18.6 What polish is not

Polish is not new features. While auditing, you will think of improvements: a search box on the quiz list, a confirmation dialog before publishing, a count of attempts per quiz. Each is a reasonable idea and each belongs in the PRD's out-of-scope list or in Chapter 20, not here. The discipline that kept the build manageable applies to finishing it too.

## 18.7 Checkpoint

> **Checkpoint.** `migrate:fresh --seed` produces a believable class: real-sounding students, your own subject matter, and populated results. Every page matches `design.md`, and `design.md` matches every page. The navbar says EduQuiz.
>
> ```bash
> git tag my-chapter-18
> ```

EduQuiz now looks like a finished product. In the next chapter it leaves your laptop.
