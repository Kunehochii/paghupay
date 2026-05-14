# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Paghupay is a web-based Guidance & Counseling system for TUP-V (Technological University of the Philippines - Visayas), deployed on local intranet. Laravel 12.x + Blade/Bootstrap 5 + PostgreSQL. Three user roles: Admin, Counselor, Client (Student).

## Commands

```bash
# Full dev environment (server + queue + logs + vite, all concurrent)
composer dev

# Run tests
composer test

# Initial setup (install, key:generate, migrate, npm build)
composer setup

# Serve only
php artisan serve

# Migrations
php artisan migrate

# Seed (creates default users + time slots)
php artisan db:seed

# Clear caches
php artisan config:clear && php artisan cache:clear && php artisan view:clear

# Lint (Laravel Pint)
./vendor/bin/pint

# Create storage symlink (required for counselor profile photos)
php artisan storage:link
```

## Architecture

### Route Groups & Middleware

Routes are strictly segregated by role in `routes/web.php`:

| Prefix      | Middleware                                | Portal    |
|-------------|-------------------------------------------|-----------|
| `/`         | `auth`, `role:client`                     | Student   |
| `/counselor`| `auth`, `role:counselor`, `verify.device` | Counselor |
| `/admin`    | `auth`, `role:admin`                      | Admin     |

Each role has a separate login page (`/login`, `/counselor/login`, `/admin/login`).

### Controller Organization

Controllers are namespaced by role under `app/Http/Controllers/{Admin,Auth,Client,Counselor}/`. Place new controllers in the appropriate role subdirectory.

### Security Middleware

- **RoleCheck** (`app/Http/Middleware/RoleCheck.php`): Validates user role against route; redirects mismatched roles to their own dashboard.
- **VerifyDevice** (`app/Http/Middleware/VerifyDevice.php`): Trust-on-First-Use (TOFU) device binding for counselors. First login binds a SHA-256 device token to the account via cookie. Admin can reset via `counselor_profiles.device_token = NULL`.

### Data Encryption

CaseLog fields (`progress_report`, `additional_notes`) and TreatmentGoal/TreatmentActivity `description` fields use Laravel's `encrypted` cast (AES-256-CBC). These fields **cannot** be searched with SQL `LIKE` — this is by design. Search by non-encrypted fields only.

### Booking Flow

4-step wizard using session state (`session('booking.*')`):
1. Choose counselor → 2. Pick date/time slot → 3. Enter reason → 4. Confirmation

Time slot availability is checked via AJAX endpoint: `GET /booking/counselor/{id}/slots?date=YYYY-MM-DD`

### Layouts

- `resources/views/layouts/app.blade.php` — main layout
- `resources/views/layouts/counselor.blade.php` — counselor sidebar layout
- Admin views use the same sidebar pattern

### Email

SendGrid SMTP via queued jobs (`QUEUE_CONNECTION=database`). Mail classes in `app/Mail/`. Templates in `resources/views/emails/`.

### File Storage

Counselor profile photos: `storage/app/public/uploads/counselors/` (accessed via `Storage::disk('public')`).

## Key Constraints

- **Local intranet deployment** — no cloud service dependencies in production
- **PostgreSQL required** — not SQLite-compatible
- **Blade + Bootstrap 5 only** — no JS framework SPA
- **Single device per counselor** — enforced by TOFU middleware
- **Encrypted fields are not searchable** — use `APP_KEY` for encryption

## Documentation

- Full specification: `docs/PAGHUPAY_SPEC.md`
- Agent context / detailed schema: `docs/AGENT_CONTEXT.md`
