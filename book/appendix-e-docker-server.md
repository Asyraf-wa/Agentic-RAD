# Appendix E — Deploying with Docker on a Server

Chapter 19 deploys to ordinary web hosting, which is what most readers have. If you control a server that runs Docker — a VPS, or a home server — you can instead deploy the same containerised stack you developed with. This appendix outlines the differences; it assumes comfort with a Linux command line.

## E.1 What changes from the development stack

The development `docker-compose.yml` is built for convenience, not production:

| Development | Production change |
| --- | --- |
| `php artisan serve` (single-threaded development server) | A production web server: PHP-FPM behind Nginx, or a single image that bundles both |
| `node` service rebuilding CSS live | No `node` service; build assets once, during the image build |
| Project folder mounted into the container | Code copied *into* the image, so the container does not depend on files on the host |
| Simple passwords in the compose file | Secrets in a server-only `.env` file, never committed |
| Port 8000 exposed directly | A reverse proxy handling HTTPS in front of the app |

## E.2 Outline

1. **Write a production Dockerfile** that installs dependencies with `composer install --no-dev --optimize-autoloader`, builds assets with `npm ci && npm run build` in a separate build stage, copies the application in, and runs a production web server.
2. **Write `docker-compose.prod.yml`** with the `app` service built from that Dockerfile, the `db` service with a persistent volume and strong passwords from an `.env` file, and no `node` service.
3. **Put a reverse proxy in front** for HTTPS. Common choices are Caddy (automatic Let's Encrypt certificates), Traefik, or Nginx with Certbot. If your server is behind a home router or you prefer not to open ports, a tunnel service such as Cloudflare Tunnel can publish the app without exposing the server directly.
4. **Deploy:** clone the repository on the server, create the production `.env` (with `APP_ENV=production`, `APP_DEBUG=false`, and `DB_CONNECTION=mysql`), then:

   ```bash
   docker compose -f docker-compose.prod.yml up -d --build
   docker compose -f docker-compose.prod.yml exec app php artisan key:generate
   docker compose -f docker-compose.prod.yml exec app php artisan migrate --force
   docker compose -f docker-compose.prod.yml exec app php artisan config:cache
   docker compose -f docker-compose.prod.yml exec app php artisan route:cache
   docker compose -f docker-compose.prod.yml exec app php artisan view:cache
   ```

5. **Back up the database volume** on a schedule, for example with a nightly `mysqldump` from the `db` container to a file copied off the server.
6. **Verify** with the checklist in Chapter 19 §19.6, which applies unchanged.

## E.3 Updating

```bash
git pull
docker compose -f docker-compose.prod.yml up -d --build
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force
```

followed by the three cache commands. Back up the database before any update that includes migrations.

Ready-to-use production files for this setup are planned for the companion repository rather than printed here, because the base images and web-server configurations they depend on change faster than a book can.
