# Laravel AI Starter

Laravel AI Starter is a modern Laravel SaaS starter kit designed for AI-powered products. It combines Laravel, MySQL, Tailwind CSS, Alpine.js, Laravel Breeze authentication, queue-based OpenAI workflows, and a polished dashboard/chat experience inside the `app` directory.

## Why this project stands out

- Production-oriented Laravel structure with thin controllers, service classes, repositories, jobs, and form requests
- ChatGPT-style AI interface with conversation history, copy actions, loading states, toast notifications, and a streaming-like typing effect
- Queue-backed AI generation flow for responsive UX and better failure handling
- Versioned REST API for dashboard stats, conversations, and messages
- Responsive dashboard with token tracking and recent activity
- Premium dark UI inspired by modern SaaS products

## Tech stack

- Laravel
- PHP
- MySQL
- Tailwind CSS
- Alpine.js
- Laravel Breeze
- Laravel Sanctum
- OpenAI PHP for Laravel
- Database-backed queues
- Docker (nginx, PHP-FPM, MySQL, MailHog, queue worker)

## Feature list

- User authentication
- Landing page, login/register, dashboard, AI chat page, settings page
- Conversation history with persistent chat threads
- Markdown AI responses
- API service layer
- REST API with versioned endpoints
- Rate limiting for AI requests
- Error handling and toast feedback
- Loading states and mobile-friendly layouts
- Environment-based OpenAI configuration
- Queue-based AI requests with worker service

## Architecture overview

The application follows a clean Laravel architecture:

- `app/Http/Controllers/Web`: dashboard, chat, landing, and settings controllers
- `app/Http/Controllers/Api/V1`: versioned API controllers
- `app/Http/Requests`: validation via Form Requests
- `app/Repositories`: conversation/message query and persistence logic
- `app/Services`: AI orchestration, dashboard aggregation, chat flow, and settings updates
- `app/Jobs`: queued AI response generation
- `app/Support/Markdown`: safe markdown rendering for assistant responses

### AI request lifecycle

1. A user submits a prompt from the web UI or REST API.
2. The application stores the user message and a pending assistant message.
3. `GenerateAiReplyJob` is dispatched to the database queue.
4. `OpenAiChatService` calls the configured OpenAI model.
5. The job stores markdown output, rendered HTML, token usage, and status.
6. The frontend polls the conversation messages endpoint and reveals the completed response with a typing effect.

## Installation

### Option 1: Docker workflow

From the repository root:

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

The app is served through nginx at [http://localhost](http://localhost).

### Option 2: Local PHP/Node workflow inside `app`

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan serve
php artisan queue:work database --tries=1 --timeout=120
```

## Environment configuration

The main variables are defined in `.env.example`.

```env
APP_NAME="Laravel AI Starter"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel_ai_starter
DB_USERNAME=root
DB_PASSWORD=root

QUEUE_CONNECTION=database

OPENAI_API_KEY=
OPENAI_MODEL=gpt-4o-mini
OPENAI_TEMPERATURE=0.7
OPENAI_BASE_URL=
```

## Queue notes

- Queue driver: `database`
- Worker service: `worker` in `docker-compose.yml`
- Job class: `app/Jobs/GenerateAiReplyJob.php`
- The queue stores pending AI work and keeps the web experience fast under load

## REST API

Authenticated endpoints are mounted under `/api/v1`.

- `GET /api/v1/dashboard`
- `GET /api/v1/conversations`
- `POST /api/v1/conversations`
- `GET /api/v1/conversations/{conversation}`
- `GET /api/v1/conversations/{conversation}/messages`
- `POST /api/v1/conversations/{conversation}/messages`

Authentication is handled through Sanctum.

## Screenshots

Add screenshots here after running the starter locally:

- `docs/screenshots/landing-page.png`
- `docs/screenshots/dashboard.png`
- `docs/screenshots/ai-chat.png`
- `docs/screenshots/settings.png`
- `docs/screenshots/mobile-chat.png`

## Testing

```bash
php artisan test
```

Included tests cover:

- authentication page access
- dashboard protection and rendering
- chat request validation
- queued AI message dispatch
- API endpoint smoke coverage
- AI job completion with mocked OpenAI service

## Project goals

This starter is intended to show recruiter-friendly, senior-level Laravel code:

- separation of concerns
- reusable UI patterns
- practical async job orchestration
- API-first thinking
- polished product execution

## License

MIT
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
