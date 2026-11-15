# Appendix C — Troubleshooting

Problems are grouped by where they appear. Each entry gives the symptom you see, the usual cause, and the fix. Entries marked **(VB)** occurred during this book's validation build.

## C.1 Docker and the environment

| Symptom | Cause | Fix |
| --- | --- | --- |
| `docker: command not found` | Docker Desktop not installed or not on PATH | Install Docker Desktop; restart the terminal |
| Docker Desktop will not start on Windows | Virtualisation disabled, or WSL 2 missing | Enable virtualisation in BIOS/UEFI; run `wsl --install` in an administrator PowerShell; restart |
| `port is already allocated` on 8000, 3306, or 5173 | Another program (often a local XAMPP or Laragon MySQL or web server) is using the port | Stop the other program, or change the left-hand port in `docker-compose.yml`'s `ports` (the one exception to never editing it; note the change) |
| `app` exits immediately; logs say it cannot reach `db` | MySQL is still initialising on first run | Wait a minute and run `docker compose up` again; the healthcheck normally handles this |
| `could not find driver` or `Connection refused` from artisan | Command run on your machine, not in the container | Prefix with `docker compose exec app` |
| Database is SQLite, or a `database.sqlite` file appears | `DB_CONNECTION` not reaching the container; Laravel's template defaults to SQLite | Use the compose file from `chapter-start` unmodified; it sets `DB_CONNECTION=mysql` for the `app` service |
| Everything is slow on Windows | Project stored on the Windows filesystem, mounted into WSL | Keep the project inside the WSL filesystem (for example `\\wsl$\Ubuntu\home\you\eduquiz`) |
| The database seems corrupted or inconsistent | Leftover state from experiments | `docker compose down -v`, then `up`, `migrate`, and `--seed` again (deletes all data) |

## C.2 Laravel

| Symptom | Cause | Fix |
| --- | --- | --- |
| `No application encryption key has been specified` | `.env` created without a key | `docker compose exec app php artisan key:generate` |
| `Table '...' doesn't exist` | Migrations not run, or a page uses a table from a later task **(VB)** | Run `migrate`; if the table belongs to a later task, the current task is overreaching (AGENTS.md rule 10) |
| `Base table or view already exists` | A migration was edited and re-run, or created twice | In development, `migrate:fresh`; then check the agent did not duplicate a migration file |
| `Add [field] to fillable property` | Model does not allow mass assignment of a field | Add the field to the model's `#[Fillable([...])]` attribute |
| `Route [name] not defined` | A view links to a route a later task creates **(VB)** | Remove the link until its task, or include the route in the current task |
| 419 *Page Expired* on form submit | Missing `@csrf` in the form, or a stale page | Check the form has `@csrf`; reload the page and resubmit |
| 403 where you expected access | Policy or guard working as written, or logged in as the wrong role | Check which user is logged in; read the policy |
| A test fails after adding a required field | An existing test submits data without the new field **(VB)** | Add the field to the test's request data (see Chapter 12, T2) |

## C.3 Tailwind and the browser

| Symptom | Cause | Fix |
| --- | --- | --- |
| New page partly unstyled; buttons invisible or colourless; grids collapsed **(VB)** | CSS bundle built before the new classes existed | Ensure the `node` service is running (`docker compose ps`); otherwise run `npm run build` |
| Styling changes not appearing | Browser cache | Hard reload (`Ctrl+Shift+R` or `Cmd+Shift+R`) |
| `Vite manifest not found` | Assets never built | Start the `node` service, or run `npm run build` |

## C.4 The agent

| Symptom | Cause | Fix |
| --- | --- | --- |
| Agent ignores the rules | Did not read `AGENTS.md` | Begin the prompt with "Read AGENTS.md and the documents in docs/" |
| Agent asks about a missing table or route **(VB)** | Task ordering issue; the agent is following rule 11 | Answer it, and fix the task in `TODO.md` |
| Agent adds a package | Did not respect rule 2 | Refuse the command; if already installed, `git restore .` (which also restores `composer.json` and `composer.lock`) |
| Agent edits `.env` or `docker-compose.yml` | Trying to "fix" a failing command | Refuse; the fault is in the code. Discard and retry |
| Same error, different fix, three times | The session is confused | Stop, `git restore .`, start a fresh session for the task |
| Report says done; verification fails | Agent tested the happy path only | Discard and retry, pasting the failing verify step into the new prompt |
| Discarded task left new files behind | `git restore .` only reverts known files | `git clean -nd` to list them, then `git clean -fd` to delete them |
| Agent writes `$fillable` instead of `#[Fillable]` | Trained on older Laravel | Not an error; both work |

## C.5 Deployment

| Symptom | Cause | Fix |
| --- | --- | --- |
| `.env` downloadable from the browser | Web root is the project folder, not `public/` | Fix the document root immediately (Chapter 19 §19.4 step 3) |
| Blank page or "Server Error" in production | Error with `APP_DEBUG=false` | Read `storage/logs/laravel.log` on the server; never enable debug publicly |
| Permission denied writing logs or cache | `storage/` or `bootstrap/cache/` not writable | Set both to `775` |
| Page unstyled in production | `public/build/` not uploaded | Run `npm run build` locally and upload `public/build/` |
| `migrate` refuses in production | Safety confirmation | Use `php artisan migrate --force` |
| Changes not visible after deploying | Cached config, routes, or views | Rerun `config:cache`, `route:cache`, `view:cache` |

## C.6 Without Docker

If your machine cannot run Docker, you can install PHP 8.3, Composer, Node 20 or later, and MySQL 8 (or MariaDB 10.11) directly; tools such as Laragon (Windows) or Herd (macOS) simplify this. Then follow the companion repository README's "Quick start (without Docker)" section, set the `DB_*` values in `.env` yourself (including `DB_CONNECTION=mysql`), and drop the `docker compose exec app` prefix from every command in the book. Tell your agent so, in `AGENTS.md`'s Commands section. You lose the guarantee that your environment matches the book's, so if you hit a problem nobody else seems to have, suspect a version difference first. The validation build itself ran this way, on MariaDB, and completed without environment-specific issues.
