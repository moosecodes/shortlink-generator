# 🔗 Shortlink Generator

A Laravel 11-based short link generator built with Jetstream, Inertia.js, and Vue 3. Easily create custom short URLs with analytics tracking—including total clicks, unique clicks, and location data by IP.

## 🚀 Features

- Custom short link creation with optional moniker suggestions
- Real-time analytics dashboard with:
  - Total and unique click counts
  - Graph of clicks over time
  - Location tracking by IP
- Edit/update existing short links
- UTM parameter support
- Fully API-driven with Vue 3 frontend
- Dockerized for easy local development

## ⚙️ Tech Stack

- **Backend:** Laravel 11, Laravel Jetstream, Inertia.js
- **Frontend:** Vue 3, TailwindCSS
- **Database:** MySQL (or PostgreSQL)
- **Auth:** Laravel Breeze (via Jetstream)
- **Containerized with:** Docker Compose
- **Dev Tools:** Sail, Postman, Laravel Telescope (optional)

## 📦 Getting Started

### Prerequisites

- Docker & Docker Compose
- Node.js (if building manually)
- Composer

### Setup Instructions

1. Clone the repo:
   ```bash
   git clone https://github.com/moosecodes/shortlink-generator.git
   cd shortlink-generator
   ```

2. Start containers:
   ```bash
   ./vendor/bin/sail up -d
   ```

3. Install dependencies:
   ```bash
   ./vendor/bin/sail composer install
   ./vendor/bin/sail npm install && npm run dev
   ```

4. Set up your `.env` file:
   ```bash
   cp .env.example .env
   ./vendor/bin/sail artisan key:generate
   ```

5. Run migrations:
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

6. Visit your app:
   ```
   http://localhost
   ```

## 🔐 API Endpoints

- `POST /api/shorten` — Create new short link
- `GET /api/analytics/{slug}` — Retrieve analytics
- `PUT /api/shortlink/{id}` — Update link
- `DELETE /api/shortlink/{id}` — Delete link

(Full API docs coming soon...)

## 📈 Future Roadmap

- 🔐 Admin dashboard with user roles
- 📤 CSV export for analytics
- 🔍 Searchable short link history
- 🧠 AI-powered moniker suggestions
- 📲 QR code generation
- 📡 Webhook support

## 🧑‍💻 Contributing

Pull requests welcome! Please follow PSR-12 and run:
```bash
composer format
```

## 🪪 License

MIT © [Mustafa](https://github.com/moosecodes)
