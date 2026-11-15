# Chapter 9 — design.md: What It Looks Like

> **What you will learn:** how to specify a user interface as exact Tailwind class strings an agent can follow; how to define layout and page content without drawing mockups; and why a design document must describe the *finished* application while each task builds only a slice of it — a lesson learned the hard way during the validation build.

## 9.1 Taste does not survive generation

Ask an agent for "a clean, modern interface" five times and you will get five clean, modern, completely different interfaces: indigo buttons on one page, blue on the next, cards with shadows here and borders there. Each page looks fine; together they look like five applications. Visual consistency is exactly the kind of property that disappears when generation is repeated, because "clean" and "modern" are taste words, and taste is the one thing a model must guess.

`design.md` replaces taste words with values. Thanks to Tailwind (Chapter 3), those values are literal strings the agent can copy and you can check by eye.

## 9.2 Principles: three sentences that rule out a lot

```markdown
Clean, academic, calm. Tailwind utility classes only — no custom CSS files,
no component libraries, no icon packages. Every page must be readable on a
phone (stack columns below `md:`).
```

The first sentence is the only taste language in the document, and it is there for you, not the agent. The second is a set of prohibitions: no custom CSS (all styling stays reviewable in the markup), no component libraries (no dependency to install and learn), no icon packages (each one is a package an agent would otherwise add without asking). The third makes the PRD's phone requirement concrete by naming the exact Tailwind breakpoint at which layouts change.

## 9.3 The palette as a lookup table

```markdown
| Use              | Tailwind classes                                         |
| ---------------- | -------------------------------------------------------- |
| Primary action   | bg-indigo-600 hover:bg-indigo-700 text-white             |
| Secondary action | bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 |
| Danger action    | bg-red-600 hover:bg-red-700 text-white                   |
| Page background  | bg-gray-100                                              |
| Card             | bg-white rounded-lg shadow p-6                           |
| Badge: draft     | bg-gray-100 text-gray-700                                |
| Badge: published | bg-green-100 text-green-700                              |
| Headings         | text-xl font-semibold text-gray-900 (page), text-lg font-medium (section) |
```

This table is the heart of the document, and its format is the point. Each row maps a *purpose* to a *class string*. When the agent builds a delete button, it does not decide what danger looks like; it looks up "Danger action." When you review, you do the reverse: see a button, find its purpose in the table, compare the strings.

Below the table, a few sentences fix the remaining shared elements: button sizing, form inputs, validation error text, and the flash-message banner. Together they cover nearly every element on every page of EduQuiz. Anything not covered is a gap — and gaps, as you know from Chapter 7, are coins the agent flips.

Notice how few colours there are: indigo for action, red for danger, green for success, grey for everything else. A small palette is easy to verify and hard to violate by accident.

## 9.4 Layout: the frame every page shares

```markdown
Top navbar (white, bottom border): app name "EduQuiz" left; nav links and
the user's name + logout right. Content inside `max-w-4xl mx-auto px-4 py-8`.
Every page: heading row (title left, primary action right where one exists),
then content.
```

One paragraph defines the frame that every page sits in. Laravel Breeze already provides a navigation bar, so this mostly confirms its shape; the content container and the heading-row pattern are what give EduQuiz its consistent rhythm. Look back at the Chapter 1 screenshots and the pattern is visible on each: page title on the left, any primary action on the right, content in a centred column.

## 9.5 Pages: content, not pixels

The last section lists every page with what it contains:

```markdown
| Page                 | Content                                                  |
| -------------------- | -------------------------------------------------------- |
| Quiz list (lecturer) | Table: title, status badge, question count, created date, actions (Manage / Edit / Delete); "New quiz" primary button |
| Quiz manage page     | Quiz title + status badge + publish/unpublish button; questions as numbered cards (text, 4 options, correct one marked); "Add question" form at the bottom |
| Take quiz            | Quiz title, numbered questions each in a card with 4 radio options, one submit button at the bottom |
| After submit         | Redirect to student quiz list with flash: "You scored X/Y on ‹title›" |
| Results (lecturer)   | Table: student name, score, submitted at; empty state: "No attempts yet" |
```

*(Abridged; ten rows in the full file, Appendix D.)*

There are no mockups and no wireframes, deliberately. A page description in words — *a table with these columns, a button here, this message when empty* — is what an agent can act on reliably, and with the palette and layout already fixed, it is enough to determine the page. Note the **empty states** ("No attempts yet"): they are the most commonly forgotten part of any interface, and an agent will not invent one unless you ask.

> **If you want mockups anyway.** For your own projects, by all means sketch pages before writing this table — on paper, or with a design tool. The sketch helps *you* decide. But translate the decision into words and class strings before the agent sees it. An image leaves the agent interpreting pixels; a sentence leaves it nothing to interpret.

## 9.6 The finished app versus the current task

Here is the most important lesson in this chapter, and it was learned during the validation build, not planned in advance.

`design.md` describes the *finished* application. The lecturer's quiz list, for example, shows a question count per quiz. But the build proceeds in small tasks, and the quiz list is built in task T4 — two tasks *before* questions exist. Following `design.md` literally, the T4 code counted each quiz's questions. The `questions` table did not exist yet; the page crashed.

Twice more the same pattern surfaced: a dashboard asked to show quiz counts before quizzes existed, and a student page whose "Take quiz" button pointed to a route that would not be created until a later chapter. Each time, the design document was correct about the *destination*; the implementation was wrong about the *timing*.

The fix was not to rewrite `design.md`. The destination is right. The fix was a single rule, now rule 10 of `AGENTS.md`, plus a matching note at the top of `TODO.md`:

> **design.md describes the finished app.** Build only the part of a page the current task asks for, even where design.md shows more; later tasks complete it.

This distinction — the *specification* describes the end state; the *roadmap* describes the order — is easy to state and easy to forget. Keep the two documents separate in your mind, and when a task touches a page whose design mentions something not yet built, expect the agent to need that reminder.

## 9.7 Checklist for your own design.md

- [ ] Principles, including what is forbidden (custom CSS, libraries, icon packages)
- [ ] A purpose-to-class-string table covering every recurring element
- [ ] Shared element rules: buttons, inputs, errors, flash messages
- [ ] Layout frame: navbar, content container, heading-row pattern
- [ ] Every page listed with its contents, including empty states
- [ ] A note — here or in `TODO.md` — that the document describes the finished application

Three documents now describe the application completely: what it does, how it is built, how it looks. Two documents remain, and both are about the agent rather than the application. The next one tells the agent how to behave.
