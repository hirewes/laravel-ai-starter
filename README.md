# Laravel AI Starter

Modern Laravel SaaS starter kit with OpenAI chat, queue jobs, REST APIs, Tailwind UI, Alpine.js interactions, conversation history, and a premium dark dashboard experience.

## Highlights

- Laravel + PHP + MySQL
- Tailwind CSS + Alpine.js
- Laravel Breeze authentication
- OpenAI integration through a dedicated service layer
- Database-backed queue jobs for AI responses
- Versioned REST API
- Mobile-friendly dashboard and chat UI

## Project layout

The application source lives in [`app`](app). Docker and infrastructure files remain at the repository root.

## Quick start

```bash
docker compose build
docker compose up -d
docker compose exec app composer install
docker compose exec app cp .env.example .env
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
docker compose exec app npm install
docker compose exec app npm run build
```

Then open [http://localhost](http://localhost).

## Documentation

Full recruiter-friendly project documentation, features, installation notes, environment variables, API details, and screenshot placeholders are available in [`app/README.md`](app/README.md).
