# Chapter 7 — PRD.md: What We Are Building

> **What you will learn:** what a Product Requirements Document is for when the reader is a machine; how to express users, roles, and features as testable statements; why the *out of scope* list is the most important section you will write; and how EduQuiz's complete PRD came to be. The full file is reproduced in Appendix D.

## 7.1 Requirements for a reader that never asks

A human developer who receives a vague brief asks questions. *Can a student retake a quiz? Do lecturers see each other's quizzes? What happens to results if a quiz is deleted?* An AI agent, by default, does not ask. It decides — instantly, plausibly, and differently each time. Every unanswered question in your requirements is a coin the agent flips on your behalf.

The Product Requirements Document exists to answer those questions before they are flipped. In conventional software projects a PRD can run to fifty pages of stakeholder prose. Yours will run to about one page, because it has one reader with one need: an unambiguous statement of *what* should exist — nothing about *how* (that is Chapter 8) or *what it looks like* (Chapter 9).

EduQuiz's PRD has five sections. Each is shown below with the reasoning behind it.

## 7.2 Overview: one paragraph of purpose

```markdown
EduQuiz is a web application where lecturers create multiple-choice quizzes
and students take them online. A lecturer builds a quiz, adds questions, and
publishes it; each student takes a published quiz once and receives an
automatic score. The application is in English, responsive, and intended for
classroom use.
```

Three sentences, and every phrase constrains. *Multiple-choice* rules out essay grading. *Once* rules out retakes. *Automatic score* rules out manual marking. *English* rules out a translation layer an eager agent might otherwise scaffold. Write your overview, then reread it asking of each word: *would the agent build something different without this?* Delete the words that fail; keep the ones that pass.

## 7.3 Users and roles: who can do what

```markdown
| Role     | Can                                                              |
| -------- | ---------------------------------------------------------------- |
| Lecturer | Create, edit, delete and publish their own quizzes; add questions; view results per quiz |
| Student  | See published quizzes; take each quiz once; see their own score  |

A user picks their role at registration. A user has exactly one role.
```

The table is short, but notice the possessives: *their own* quizzes, *their own* score. Those two words generate an entire layer of authorization code — one lecturer must not edit another's quiz — which the agent will implement only because the requirement says so. The closing two sentences settle questions agents otherwise answer with elaborate machinery: no admin approving roles, no user holding both roles, no permissions package. (Chapter 10's `AGENTS.md` will forbid that package explicitly; the PRD makes it unnecessary.)

## 7.4 Functional requirements: numbered and testable

EduQuiz's PRD lists eleven functional requirements, `FR-1` to `FR-11`. Three of them show the pattern:

```markdown
- **FR-4 Questions**: a lecturer adds questions to their own quiz; each question
  has question text and exactly 4 options, exactly one marked correct;
  questions can be deleted
- **FR-5 Publishing**: a lecturer toggles a quiz between `draft` and `published`;
  only quizzes with at least 1 question can be published
- **FR-9 One attempt**: a student who has attempted a quiz cannot take it again;
  the take page is blocked
```

Two properties make a requirement fit for an agent.

**It is testable.** "Exactly 4 options, exactly one marked correct" can be checked by counting rows in a database. "Questions should have sensible options" cannot. If you cannot imagine the one-line check that would prove a requirement met, the agent cannot build to it either. Later, every `Verify` line in `TODO.md` (Chapter 11) will trace back to one of these numbered requirements.

**It is numbered.** Numbering looks bureaucratic, but it gives you a vocabulary for review. When an agent's output is wrong, "this violates FR-9" is a one-line correction it can act on; "the retake thing is off" is not.

Notice also what FR-5 contains: a business rule hiding inside a feature — *at least 1 question*. Rules like this are where agent output most often goes wrong, because they are the parts a plausible-looking implementation can silently omit. Write every such rule into a requirement explicitly. The validation build's test for FR-5 tries to publish an empty quiz and confirms it stays a draft; that test exists only because the rule was written down.

## 7.5 Out of scope: the most important section

```markdown
Per-question answer review, timers, true/false and short-answer questions,
points per question, question reordering, multiple attempts, categories or
tags, email verification, password reset, file uploads, UI languages other
than English.
```

If you remember one thing from this chapter, make it this: **agents over-build.** Trained on millions of complete applications, a model asked for "a quiz app" reaches for the features quiz apps usually have — timers, categories, question banks, analytics. Each is individually reasonable. Together they triple the code you must review, introduce bugs in features nobody asked for, and make every reader's application different from every other's.

The out-of-scope list is your defence. It turns the agent's default expansiveness into explicit prohibitions, and it doubles as a design record: the list above is, almost line for line, the exercise list for Chapter 20. Nothing here was cut for being a bad idea. It was cut because version one must be small enough to verify completely.

Two practical rules for writing yours:

- **List the features a reasonable person would expect** — the expectation is precisely what the agent shares.
- **List the boring infrastructure too.** Email verification and password reset are not glamorous, but Laravel's scaffolding offers them and an agent will happily wire them up, along with an email server configuration you then have to maintain.

> **A note on password reset.** Breeze, the scaffolding installed in Chapter 12, ships a password-reset page whether you want it or not. Listing password reset as out of scope does not delete that page; it tells the agent not to spend effort making it work (it needs an email server). This is a useful distinction: out of scope means *not built on purpose*, not *must not exist*.

## 7.6 Non-functional requirements: the quiet constraints

```markdown
Responsive layout (usable on a phone), seeded demo data (1 lecturer,
5 students, 2 quizzes), all pages render without JavaScript except where
Laravel's defaults require it.
```

Non-functional requirements describe *qualities* rather than features. EduQuiz's three are chosen for the classroom. Phone usability matters because students will take quizzes on their phones. Seeded demo data matters because an empty application cannot be demonstrated. And "without JavaScript" quietly forbids the agent from reaching for a front-end framework, keeping the whole application inside the Blade-and-Tailwind literacy of Chapters 2 and 3.

## 7.7 How the PRD gets used

The PRD is not read once and filed. It enters the build in three ways:

1. **As agent context** — every task instruction in Part IV begins "Read the documents in `docs/`," so the agent consults the PRD each time.
2. **As the source of verification** — each `TODO.md` task's check derives from a numbered requirement.
3. **As the arbiter in disputes** — when the agent's output and your memory disagree about what was intended, the PRD wins. If the PRD is silent, that silence is a bug in the PRD; fix it there, not in a chat message the agent will forget.

That third point deserves emphasis. A correction typed into an agent's chat panel lives for one session. A correction written into `PRD.md` lives in Git, applies to every future task, and reaches every reader of the repository. **Fix intent at its source.**

## 7.8 Exercise: write a PRD for something else

Before reading on, draft a one-page PRD for a different small educational application — a class attendance tracker, a reading-list manager, a peer-review exchange. Use the five-section shape:

1. Overview (one paragraph; every word constraining)
2. Users and roles (a table; mind the possessives)
3. Functional requirements (numbered, each testable)
4. Out of scope (at least eight items; include the boring infrastructure)
5. Non-functional requirements (two to four qualities)

Then trade with a classmate and try to find a question the PRD does not answer. Each one you find is a coin an agent would have flipped.
