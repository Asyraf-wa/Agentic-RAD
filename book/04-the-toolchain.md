# Chapter 4 — The Toolchain

> **What you will learn:** the three layers of your toolchain and which of them this book depends on; how to set up VS Code with an AI agent — GitHub Copilot by the main road, Cline by the free open-source road; why educators and students should claim GitHub Education before anything else; and how the book stays valid when the tools inevitably change.

## 4.1 Three layers, one dependency

Your working environment has three layers:

1. **The editor** — Visual Studio Code. Free, cross-platform, and host to every agent this book mentions.
2. **The agent** — the AI that reads your documents and edits the project: GitHub Copilot in agent mode, or Cline, or a successor that did not exist when this book went to print.
3. **The method** — the five documents and the task loop.

Only the third layer is this book's real dependency. The chapters are written so that the agent is interchangeable: every instruction you give it in Part IV is of the form *"Read the documents in `docs/`. Complete task T6 in `docs/TODO.md`."* — a sentence any agentic tool can act on. Where a demonstration needs a concrete tool, the text describes Copilot; where a step differs by tool, the walk-throughs live in **Appendix A (Copilot)** and **Appendix B (Cline)**, which the companion repository keeps current between printings.

So treat this chapter as a map, and the appendices as the turn-by-turn directions.

## 4.2 Visual Studio Code

Install VS Code from `code.visualstudio.com` for your operating system; accept the defaults. Two extensions are worth adding immediately regardless of agent choice: a PHP language extension (search the marketplace for "PHP" — Intelephense is a common choice) and the official "Tailwind CSS IntelliSense" extension, which makes class strings readable and autocompletable while you review. Neither is required; both make the reviewing half of your job pleasanter.

## 4.3 The main road: GitHub Copilot agent mode

GitHub Copilot lives inside VS Code as an extension, signed in with your GitHub account. It has three modes; the names matter because only one of them is this book's subject:

- **Completions** — grey ghost-text while you type. Not our concern.
- **Chat (ask/edit)** — a Q&A panel that can propose edits to files you select.
- **Agent mode** — the one this book means: you give it a goal, it plans, opens and edits files across the project, runs terminal commands (migrations, tests), reads the output, and iterates, showing you each step for approval.

Agent mode reads `AGENTS.md` from the repository root automatically — which is precisely why Chapter 10 puts the behaviour manual there. When you hand it a task in Part IV, it will have your rules in front of it without being asked.

**Cost, and the paragraph that will age fastest in this book.** As of this writing, Copilot has a free tier for individual accounts, but it is metered: agent-mode requests draw from a small monthly allowance of "premium requests" (a few dozen at the time of writing) — enough to try the workflow, not enough to finish EduQuiz. The paid individual plan removes the practical ceiling. **But check Appendix A and the companion repository before paying anything**: the quota numbers, plan names, and sign-up flows have changed several times in recent years and will change again.

## 4.4 Claim GitHub Education first

If you teach, or your readers are students, this section may be worth the book's price. Through **GitHub Education** (`github.com/education`), verified teachers and students receive **Copilot Pro at no cost** — the paid tier, with agent mode and quotas that comfortably cover this book and a semester of coursework besides.

Verification requires an academic email address or documentation (a staff card, an enrolment letter) and is done once per account. For an IMS566-style cohort, have every student complete verification *before* week one; approval is sometimes not instant, and a student waiting on verification in week three is a student blocked. The step-by-step flow — which GitHub revises periodically — is in Appendix A.

## 4.5 The free, open road: Cline

If Copilot's terms do not fit — no academic affiliation, an institution that forbids the subscription, or a preference for open source — the documented alternative is **Cline**, an open-source agentic extension for VS Code.

Cline's model is different in one structural way: the extension is free and the intelligence is **bring-your-own**. You connect it to a model through an API key — a commercial provider's free tier, a low-cost paid key, or even a model running on your own machine for the committed. Cline reads `AGENTS.md`-style instruction files, plans, edits, and runs commands with per-step approval, exactly the loop this book teaches. Its "plan then act" structure — it proposes an approach before touching files — maps neatly onto the review discipline of Part IV.

Which free or cheap model to plug in is the least stable fact in this entire book — the leaderboard reshuffles quarterly — so Appendix B describes how to choose and test one, and the companion repository's copy of that appendix is updated as the landscape moves.

The instructions in Parts III–V never assume which road you took. "Give the agent task T6" means the same sentence in Copilot's agent panel or Cline's.

## 4.6 What about everything else?

Readers will reasonably ask about other agentic tools — terminal-based agents, other editors' built-ins, whatever launched last month. The honest answer: if it can (a) read instruction files from the repository, (b) edit files and run commands with your approval, and (c) accept a one-sentence task, it can run this book's method. The five documents were designed as the *portable* part. A tool that cannot read `AGENTS.md` automatically can simply be told to read it — one extra sentence per session. Nothing in Part IV breaks.

> **Why the book is not tool-neutral in its examples.** Demonstrations have to show something. Copilot was chosen because it is the tool a reader is likeliest to already have, and because GitHub Education makes it free for this book's primary audience. The choice is pedagogical convenience, not endorsement — as the existence of Appendix B attests.

## 4.7 The versioning contract

A book about fast-moving tools owes its readers a maintenance policy. This one's:

- **Chapters** contain only what is stable: the method, the documents, the loop, the project.
- **Appendices A–C** contain what goes stale: quotas, sign-up flows, menu names, model recommendations, troubleshooting for specific versions — each stamped with an "as of" date.
- **The companion repository** (`github.com/Asyraf-wa/Agentic-RAD`) carries the living copies of those appendices, plus the pinned, tested environment. When reality and the printed page disagree, trust the repository.

## 4.8 Checklist before Chapter 5

- [ ] VS Code installed and opening.
- [ ] A GitHub account; educators and students: GitHub Education verification submitted.
- [ ] An agent installed and signed in — Copilot (Appendix A) or Cline with a model key (Appendix B).
- [ ] Sanity check passed: ask your agent, in its chat, *"What files are in this folder?"* with any folder open. A sensible answer means the agent is alive and can see your workspace.

The agent now has a brain and your editor is ready. What it does not yet have is a body — a running PHP, a database, a place where EduQuiz can live. That is Docker's job, and it is deliberately the next chapter rather than a troubleshooting appendix: the environment is where beginners' courage goes to die, so we are going to make it a non-event.
