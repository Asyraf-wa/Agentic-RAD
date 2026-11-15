# Rapid Web Application Development Using Agentic AI

### Building Educational Web Applications with Laravel, Docker, Git, and AI Coding Agents

**Dr. Muhammad Asyraf Wahi Anuar**
Faculty of Information Science
Universiti Teknologi MARA (UiTM)

---

## Preface

Something fundamental has changed in how software gets built. For most of my fifteen-plus years of developing web applications and teaching others to do the same, the bottleneck was typing: translating an idea, line by line, into working code. Today, an AI coding agent can do most of that typing. What it cannot do is know what you want, and that has quietly become the new skill — expressing intent so precisely that a machine can act on it, and verifying the result so carefully that you can trust what came back.

This book teaches that skill through a single, complete project. You will build **EduQuiz**, a quiz application for lecturers and students, from an empty folder to a deployed open educational tool. You will not type most of the code. Instead, you will write five short documents — a requirements document, a system design, a visual specification, a behaviour manual for your AI agent, and a task roadmap — and then direct the agent through the build, one small task at a time, verifying and committing as you go.

Three ideas run through every chapter:

**Reproducible outcome, not identical code.** AI agents are not deterministic. Run the same instruction twice and you will get different code. This book does not fight that; it works with it. The five documents constrain what the agent can produce, each task carries an explicit verification step that defines "done", and a Git tag at every chapter boundary means you are never more than one command away from a known-good state. Every reader finishes with the same working application, even though no two readers' code will be character-for-character identical.

**The reader is never more than one small step from a working app.** Every chapter ends with the application running and something new visible in the browser. The development environment comes ready-made — you clone it and start it; you never debug it. When an agent goes off the rails (it will, occasionally), recovery is one Git command.

**Everything in this book actually happened.** Before these chapters were written, the entire build was executed, task by task, exactly as the roadmap orders it: all fourteen tasks, verified by an automated test suite of thirty-six tests, against the same five documents printed in Appendix D. The places where the process stumbled — a scaffolding test that broke when a field was added, a stale stylesheet that made a button invisible, a task that asked for data that did not exist yet — are not edited out. They are the most instructive parts, and you will find each one discussed where it occurred.

The book is written first for my own students in IMS566 (Advanced Web Design Development and Content Management) at UiTM, and the pacing fits a fourteen-week semester with room to spare. But nothing in it assumes you are in that room: a developer or educator working alone can follow every step, and the companion repository at **github.com/Asyraf-wa/Agentic-RAD** carries the environment, the documents, the finished code, and a checkpoint tag for every chapter.

A note on tools: the demonstrations use GitHub Copilot's agent mode in Visual Studio Code, which is free for verified teachers and students through GitHub Education, with an open-source alternative (Cline) documented in Appendix B. But the method is deliberately tool-agnostic. Free tiers, model names, and menu layouts change faster than books reprint; the workflow — five documents, small tasks, verify, commit — does not. Everything volatile lives in the appendices and the companion repository, where it can be kept current.

Selamat membina — happy building.

*Shah Alam, 2026*

---

## How to use this book

- **Part I — Foundations** (Chapters 1–3) explains the agentic workflow and teaches you to *read* Laravel and Tailwind — enough to verify an agent's output, which is a smaller and different skill than writing from scratch.
- **Part II — The workbench** (Chapters 4–6) sets up your tools. By the end of Chapter 5 the application skeleton is running on your machine in Docker; Chapter 6 adds the Git safety net.
- **Part III — The method** (Chapters 7–11) is the heart of the book: writing the five documents that direct the agent.
- **Part IV — The build** (Chapters 12–17) executes the roadmap. Each chapter follows the same loop — give the agent one task, verify, commit — and ends with the app working and tagged.
- **Part V — Ship and extend** (Chapters 18–20) seeds demo data, deploys to ordinary web hosting, and hands the method over to you with guided extension exercises.
- **Appendices** hold everything that goes stale: tool setup, troubleshooting, and the five documents in full.

Boxed elements recur throughout:

> **Checkpoint.** Appears at the end of each build chapter: the Git tag to which the companion repository is wound at that moment, and what you should see in the browser.

> **When the AI gets it wrong.** Real failures from this book's own validation build, with the recovery steps.

> **Concept box.** Just-in-time explanation of the Laravel or Tailwind idea that the code the agent just generated depends on.
