# Video Library

## To run the local dev environment:

### API
- Ensure version docker installed is active on host
- Copy .env.example: `cp .env.example .env`
- Start docker containers `docker compose up` (add `-d` to run detached)
- Connect to container to run commands: `docker exec -it video-library_app_1 bash`
  - Make sure you are in the `/var/www/html` path
  - Install php dependencies: `composer install`
  - Setup app key: `php artisan key:generate`
  - Setup JWT secret key: `php artisan jwt:secret`
  - Migrate database: `php artisan migrate` 
  - Seed database: `php artisan db:seed`
  - Run tests: `php artisan test`
- Swagger documentation: `http://localhost:8000/api/documentation`
- Domain: `http://localhost:8000/api`

