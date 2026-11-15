# Chapter 19 — Deploying as an Open Educational Tool

> **What you will learn:** what changes between development and production; how to prepare a Laravel application for ordinary web hosting; how to deploy to a cPanel-style host or a simple VPS; how to keep secrets and debug output away from the public; and how to publish EduQuiz as an open educational resource others can reuse.

## 19.1 Development was the easy environment

For eighteen chapters, Docker gave you a perfect environment: pinned versions, a database that configured itself, a container rebuilding CSS on every change. Production is not like that. A typical Malaysian university department, or a lecturer paying for their own hosting, will deploy to **shared hosting** with a control panel such as cPanel, or to a small **virtual private server (VPS)**. Neither runs your `docker-compose.yml`.

That is fine, and it is the reason this chapter exists. Docker defined the *development contract*: what versions, what services, what commands. Deployment is the act of honouring that contract on a machine you do not fully control. (If you do control the machine, Appendix E covers deploying the Docker stack itself.)

## 19.2 What changes in production

Four differences account for nearly every deployment problem:

| Concern | Development | Production |
| --- | --- | --- |
| Error display | Full error pages with code and stack traces | A plain "Server Error" page; details only in logs |
| CSS and JavaScript | Rebuilt live by the `node` container | Built **once**, uploaded as static files |
| Database | Created by Docker, filled by the seeder | Created by you in the host's panel; **never** seeded |
| Web root | `php artisan serve` serves everything | The web server must point at the `public/` folder only |

The first row is a security matter. In development, a crash shows file paths, configuration values, and sometimes database credentials, because that helps you debug. In production, the same page would hand that information to anyone. Turning it off is the single most important deployment step.

## 19.3 Prepare the build on your machine

Shared hosting often lacks Node, and sometimes lacks shell access. So do the building at home, in your Docker environment, and upload the results.

**1. Run the tests one last time.** A deployment that starts from a red suite is a deployment you will debug in public:

```bash
docker compose exec app php artisan test
```

**2. Build the front-end assets.** Run the production CSS and JavaScript build:

```bash
docker compose exec node npm run build
```

This writes compiled, minified files to `public/build/`. In production, these files *are* the stylesheet; the stale-CSS failure of Chapter 3 cannot happen, because nothing rebuilds. If you change a view's classes later, rebuild and re-upload.

**3. Install production dependencies only.**

```bash
docker compose exec app composer install --no-dev --optimize-autoloader
```

`--no-dev` leaves out development-only packages — including Breeze, which has done its job; the files it generated are already part of your code. `--optimize-autoloader` makes class loading faster. This step changes the `vendor/` folder in your project, so after you have uploaded, run a plain `composer install` again to restore the development packages (the test tools among them).

## 19.4 Deploying to shared hosting (cPanel-style)

Control panels differ between hosts, so the steps below describe *what* to do; your host's documentation will show *where* to click. Before starting, confirm with your host that PHP 8.3 or later is available and selectable for your account.

**1. Create the database.** In the panel's MySQL section, create a database and a user, give the user all privileges on that database, and note the three values: database name, username, password. Hosts often prefix names with your account name, such as `myaccount_eduquiz`.

**2. Upload the application.** Compress your project folder — excluding `node_modules/`, `.git/`, and your local `.env` — and upload it with the panel's file manager. Extract it **outside** the public web folder: for example, to `/home/myaccount/eduquiz/`, beside `public_html/`, not inside it.

**3. Point the web root at `public/`.** This is the step most often done wrong. Only the contents of Laravel's `public/` folder should be reachable from the web. Everything else — `.env`, `app/`, `vendor/` — must not be. Depending on your host, either:

- set the domain's or subdomain's **document root** to `/home/myaccount/eduquiz/public` (the cleanest option, available in most panels for addon domains and subdomains), or
- copy the contents of `public/` into `public_html/` and edit `public_html/index.php` so its paths point into `../eduquiz/` instead of `../`.

Test the result: browsing to `https://yourdomain/.env` must **not** show your configuration. If it does, the web root is wrong; stop and fix it before continuing.

**4. Create the production `.env`.** Copy `.env.example` to `.env` on the server, with the panel's file editor, and set:

```ini
APP_NAME=EduQuiz
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=myaccount_eduquiz
DB_USERNAME=myaccount_eduquizuser
DB_PASSWORD=the-password-you-set
```

`APP_DEBUG=false` is the security setting from §19.2. Never set it to `true` on a public server, even briefly to diagnose a problem; read the log at `storage/logs/laravel.log` instead. Note `DB_CONNECTION=mysql`: Laravel's template defaults to SQLite, and in Docker the compose file overrode it for you. Here nothing does.

**5. Finish with Artisan.** If your host offers a terminal (many cPanel hosts do, under *Terminal* or via SSH), run from the application folder:

```bash
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

`--force` is required because Laravel refuses to migrate in production without it — a deliberate safety catch. The three `cache` commands precompile configuration, routes, and views for speed. Run them again after every deployment.

Note what is absent: `--seed`. The production database starts empty, and real lecturers register real accounts.

**6. Permissions.** Laravel writes logs, caches, and sessions to `storage/` and `bootstrap/cache/`. If you see a permission error, set those two folders to be writable by the web server (usually permission `775` in the file manager).

> **No terminal at all?** Some budget hosts offer neither SSH nor a terminal. You can still deploy: generate the application key locally and paste it into the server's `.env`, and run the migrations against an exported copy of your local schema, imported through the panel's database tool. It works, but it is fragile. For a course or department, a host with terminal access is worth the small extra cost.

## 19.5 Deploying to a simple VPS

On a VPS you have full control, which means more steps and fewer constraints. The shape is the same: install PHP 8.3 with the required extensions, a web server (Nginx or Apache), and MySQL; clone your repository from GitHub; create the production `.env`; run `composer install --no-dev`, the asset build, and the five Artisan commands; and point the web server's document root at `public/`. Then add HTTPS with a free certificate from Let's Encrypt. The exact commands depend on the operating system version and change over time, so follow your distribution's current guides for each component.

If you already run Docker on your server, Appendix E describes the containerised alternative, which reuses most of your development setup.

## 19.6 After going live

Verify the deployment exactly as you verified each task:

- [ ] `https://yourdomain/.env` returns "not found" or "forbidden", never your settings
- [ ] Register a lecturer, create and publish a quiz with one question
- [ ] Register a student in a private browser window, take the quiz, see the score
- [ ] As the lecturer, see the student on the results page
- [ ] Trigger a 404 by visiting a nonsense URL: you should see a plain error page, with no code or file paths

The last check confirms `APP_DEBUG=false` is in effect.

**Updating.** When you change the application later, the cycle is: develop and verify locally, commit, rebuild assets, upload changed files (or `git pull` on a VPS), run `php artisan migrate --force` if there are new migrations, and rerun the three cache commands. Back up the production database before any migration.

## 19.7 Publishing as an open educational resource

EduQuiz was built for teaching, and the method behind it is the more valuable part. Publishing both lets other educators reuse and adapt them.

**Choose a licence.** Code and teaching materials usually take different licences:

- For the **code**, a permissive software licence such as MIT lets anyone use, modify, and redistribute it with attribution.
- For the **documents and teaching material** — the five documents, any lesson plans — a Creative Commons licence is conventional for open educational resources. CC BY permits reuse with attribution; CC BY-SA additionally requires that adaptations carry the same licence.

Add a `LICENSE` file to the repository, and state in the README which licence covers what. If your institution has an open educational resources policy — UiTM's iOER initiative is one example — check its requirements first.

**Write the README for strangers.** Your repository's README should let someone who has never seen this book run the application. The companion repository's README is a model: what the application is, the five documents and their purposes, how to start it, the demo accounts, and how to run the tests.

**Keep the documents in the repository.** This is the distinctive part. Most published applications include only the code. Yours includes the PRD, architecture, design specification, behaviour manual, and roadmap that produced it — so another educator can not only run EduQuiz but *rebuild it with their own agent*, or change the PRD and build something different. You are publishing a reproducible method, not just an artefact.

## 19.8 Checkpoint

> **Checkpoint.** EduQuiz is live on a public address, with debug output off, the web root pointing at `public/`, and HTTPS working. The repository is public with a licence and a README that a stranger can follow.

One chapter remains, and it is the one where the book steps back and you build on your own.
