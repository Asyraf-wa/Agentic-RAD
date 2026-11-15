# Appendix B — Setting Up Cline with a Free or Low-Cost Model

> **As of November 2026.** Model availability and free-tier terms change frequently, more than any other subject in this book. The companion repository keeps the current version of this appendix.

## B.1 How Cline differs

Cline is an open-source agentic extension for VS Code. The extension itself is free. It does not include a model: you connect it to one, through an API key from a model provider or a model running on your own computer. This gives you choice and avoids subscription lock-in, at the cost of one more setup step and some judgement about which model to use.

Cline works in two phases that suit this book's method well:

- **Plan mode** — Cline reads the documents and proposes an approach, without changing files. Use it to check that the agent has understood a task before it starts.
- **Act mode** — Cline carries out the plan, editing files and proposing commands, each with your approval.

## B.2 Install

1. In VS Code's Extensions view, search for **Cline** and install it.
2. Open the Cline panel from its icon in the activity bar.
3. Open the settings (gear icon) to choose an API provider and enter a key.

## B.3 Choose a model

You need a model that is strong at coding and supports tool use (the ability to call functions such as "edit file" or "run command"). Three broad routes:

| Route | Cost | Notes |
| --- | --- | --- |
| A provider's free tier | Free, rate-limited | Several providers offer free access to capable models with daily or per-minute limits. Adequate for a task or two a day. |
| A paid API key | Pay per use | Check the provider's pricing before starting, and watch Cline's per-task cost display. |
| A local model | Free, needs hardware | Runs on your computer via a local model server. Requires a capable machine; smaller local models may struggle with the larger tasks such as T6. |

Whatever you choose, test it before Chapter 12. Open the EduQuiz folder and ask Cline, in plan mode: *"Read AGENTS.md and docs/TODO.md. Summarise task T6 and list the files you would create."* A model that produces a sensible plan is likely to cope with the build; one that cannot is better replaced now than in the middle of Part IV.

## B.4 Point Cline at AGENTS.md

Check that Cline is reading your behaviour manual by asking: *"What rules does AGENTS.md give you?"* If it does not find them, start each task prompt with "Read AGENTS.md and the documents in docs/," as this book recommends anyway.

## B.5 Working habits

- **Plan first on larger tasks.** For T6, T10, and T11, use plan mode first and check the plan against the task before switching to act mode.
- **Watch the cost or rate limit.** Cline shows usage per task. A task that keeps consuming requests without progress is a sign to stop, discard, and restart, as Chapter 12 §12.6 describes.
- **Keep auto-approve off.** Cline can be configured to approve edits or commands automatically. Leave this off while following the book.
