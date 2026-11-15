# Chapter 5 — Containers, Not Configuration

> **What you will learn:** why this book refuses to let you configure a development environment by hand; just enough Docker to not find it mysterious; and the commands that take you from nothing to a running Laravel application. This chapter ends with EduQuiz's skeleton alive in your browser.

## 5.1 The graveyard of week one

Every web development instructor knows where beginners are lost. It is not the loops, not the database — it is the environment. One student's PHP is 8.1, another's is 8.4; one machine's MySQL refuses connections; a third works until an update. In a class of thirty you can spend two weeks as a roaming IT department before anyone writes software — and a self-learner has no roaming IT department, so they quit.

Agentic development raises the stakes further. Your AI agent will run migrations and tests *on your environment*; if each reader's environment differs, each reader's agent behaves differently, and the reproducibility promise of Chapter 1 collapses at its foundation.

This book's answer is total: **you do not configure a development environment. You clone one.** The companion repository contains the environment as code, and Docker runs it identically on Windows, macOS, and Linux. The rule stated in `AGENTS.md` applies equally to you and the agent: nobody edits the environment files. If the environment is broken, you restart it; you never repair it.

## 5.2 Enough Docker to trust it

Docker runs applications in **containers** — isolated boxes, each holding a program plus the exact system it needs, sharing your machine without touching it. A container is born from an **image** (a frozen snapshot: "PHP 8.3 with these extensions"), and a file called `docker-compose.yml` declares a *set* of containers that start together as one stack.

EduQuiz's stack declares three services:

| Service | Image | Job |
| --- | --- | --- |
| `app` | PHP 8.3 (built from `docker/Dockerfile`) | Runs Laravel itself |
| `db` | `mysql:8.0` | The database |
| `node` | `node:22` | Runs Vite, continuously rebuilding Tailwind's CSS |

Notice `node`: Chapter 3 warned you about stale CSS builds; this container is the reason you should rarely meet that failure — it rebuilds the bundle automatically whenever a template changes, as long as the stack is up.

Your project folder is *mounted* into the containers, which produces the only mental model you need: **files live on your machine — edited by you, by your agent, versioned by Git — while programs run in the containers.** Deleting the containers loses nothing but a running process; your code and even the database's data (kept in a named Docker *volume*) survive restarts.

> **Concept box: why not install PHP directly?** You could — Appendix C sketches it for machines that cannot run Docker. The costs are exactly the ones this chapter exists to avoid: your versions drift from the book's, your classmates' machines drift from yours, and Chapter 19's deployment story ("develop in a pinned environment, then ship") loses its first half. Docker is not ideology here; it is the reproducibility promise, mechanised.

## 5.3 Install Docker Desktop

Install **Docker Desktop** from `docker.com` (on Windows it will ask to enable WSL 2 — accept). The install is ordinary; the one verification that matters is typing `docker compose version` in a terminal and getting a version number back. Machine-specific wrinkles — BIOS virtualisation flags, corporate antivirus, Apple Silicon notes — are a troubleshooting matter and live in Appendix C, not here.

## 5.4 From nothing to running

Open a terminal in the folder where you keep projects, and:

```bash
git clone https://github.com/Asyraf-wa/Agentic-RAD.git eduquiz
cd eduquiz
git switch -c my-build chapter-start
docker compose up
```

The `chapter-start` tag is the bare starting point: the Laravel skeleton, the Docker environment, and the five documents, with nothing yet built; `git switch -c my-build` starts your own branch, `my-build`, from it. (The repository's latest version is the *finished* EduQuiz; you want to build it, not download it.)

The first run downloads images and builds the `app` container — minutes, once. The terminal then fills with the stack's combined chatter: MySQL initialising, Vite reporting ready, and Laravel's server announcing itself. When the noise settles, open a second terminal in the same folder (the first one is now the stack's log window — leave it running) for the one-time application setup:

```bash
docker compose exec app composer install
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

Read the pattern once, because you will see it all book long: `docker compose exec app …` means "run this command *inside the app container*." Composer installs Laravel's packages; `.env` is created from its template; the application generates its encryption key; the migrations build the schema. You do not need to edit `.env` to reach the database — the compose file passes the connection settings to the container directly, and they take precedence.

Now the moment this chapter exists for. Browse to:

```text
http://localhost:8000
```

Laravel's welcome page. EduQuiz's skeleton is alive on your machine, served by a PHP you never installed, backed by a MySQL you never configured.

## 5.5 Operating the stack

Everything you need for the rest of the book:

| You want to | Command |
| --- | --- |
| Start the stack | `docker compose up` (or `-d` to run without the log window) |
| Stop it | `Ctrl-C` in the log window, or `docker compose down` |
| Run artisan / composer / anything in the app | `docker compose exec app …` |
| See the logs when something is odd | `docker compose logs app` (or `db`, `node`) |
| The nuclear option: fresh containers | `docker compose down && docker compose up` |
| The thermonuclear option: fresh database too | `docker compose down -v`, then up and re-migrate |

That last row deletes the database volume — demo data and all — and is the correct response to a database that seems haunted. It takes a minute and cannot harm your code.

> **When the AI gets it wrong (pre-emptively).** Your agent runs commands in a terminal — but *which* terminal? If it runs `php artisan migrate` on your bare machine rather than through `docker compose exec app`, it will either fail (no PHP installed — harmless, and now you know) or, worse, quietly use some other PHP on your system. `AGENTS.md` therefore spells out the command form the agent must use. If an agent reports a mysterious "could not connect to database," check *where* it ran the command before suspecting the database. More in Appendix C.

## 5.6 What just happened, and what did not

Worth pausing on what you did **not** do: install PHP, choose extensions, configure a database server, create a database user, or edit a single config file. Thirty readers who finish this chapter have thirty *identical* environments, down to the minor version — and so do their thirty agents. The first pillar of reproducibility is set.

You also met, in passing, the second pillar: those commands began with `git clone`. The same tool that delivered the environment will be your safety net when the agent misbehaves — and that is the next chapter.

> **Checkpoint.** The stack starts with `docker compose up`; `http://localhost:8000` shows Laravel's welcome page; `docker compose exec app php artisan --version` prints a Laravel 13 version. If any of the three fails, Appendix C's first table is for you — and nothing in Part III onward is worth starting until all three pass.
