# Scraper

## Setup

### Dev Setup
PHP and composer installation is needed for dev environment setup

1. Clone repo and install packages
```bash
composer install --working-dir=api
```
2. Copy `api/.env.dev.example` to `api/.env`, `web/.env.dev.example` to `web/.env`
```bash
cp api/.env.dev.example api/.env && \
cp web/.env.dev.example web/.env
```
3. Run the application
```bash
./api/vendor/bin/sail -f docker-compose.dev.yml up -d
```
4. Generate api app key
```bash
./api/vendor/bin/sail artisan key:generate
```
5. Run database migrations
```bash
./api/vendor/bin/sail artisan migrate 
```
6. App is accessible at http://localhost:5173 
7. Running the scraper
```bash
./api/vendor/bin/sail artisan app:scrape-articles
```

### Prod Setup 

1. Copy `api/.env.prod.example` to `api/.env`, `web/.env.prod.example` to `web/.env`, `docker/prod/web/default.example.conf` to `docker/prod/web/default.conf` and replace all instances of `domain_placeholder` with the domain your app will use
```bash
cp api/.env.prod.example api/.env && \
sed -i "s|domain_placeholder|scraper.local|g" api/.env

cp web/.env.prod.example web/.env && \
sed -i "s|domain_placeholder|scraper.local|g" web/.env

cp docker/prod/web/default.example.conf docker/prod/web/default.conf && \
sed -i "s|domain_placeholder|scraper.local|g" docker/prod/web/default.conf
```
2. (Optional) Update `docker-compose.yml` db service with more secure credentials and reflect these changes in the `api/.env` file
3. Run the application
```bash
docker compose up -d
```
4. Generate api app key
```bash
docker compose exec -u $(id -u):$(id -g) api php artisan key:generate --force
```
5. Run database migrations
```bash
docker compose exec api php artisan migrate --force
```
6. App is accessible at http://{domain} where `{domain}` is the previously used domain
7. Running the scraper
```bash
docker compose exec api php artisan app:scrape-articles
```

### Testing prod build locally
1. Add {domain} and api.{domain} entries pointing to 127.0.0.1 in the hosts file
```
127.0.0.1	scraper.local
127.0.0.1	api.scraper.local
```

### TODO's
- SSL certificates for prod build
