# AGENT_CONTEXT.md - Paghupay (TUP-V Guidance & Counseling System)

> **For AI Agents**: This file provides a complete structural overview of the Paghupay application. Read this first before making any changes.

---

## 📋 Project Overview

**Paghupay** is a web-based Guidance and Counseling system for TUP-V (Technological University of the Philippines - Visayas), deployed on a **Local Intranet**. It serves three user roles: **Admin**, **Client (Student)**, and **Counselor**.

### Tech Stack

-   **Framework**: Laravel 12.x (PHP 8.2+)
-   **Frontend**: Blade Templates + Bootstrap 5
-   **Database**: PostgreSQL
-   **Email**: SendGrid (SMTP)
-   **File Storage**: Local (storage/app/public)
-   **Deployment**: Local Server (No cloud dependencies)

---

## 🗂️ Directory Structure

```
paghupay/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── CounselorController.php    # Counselor CRUD + device reset
│   │   │   │   ├── ClientController.php       # Client management
│   │   │   │   └── DashboardController.php    # Admin dashboard
│   │   │   ├── Auth/
│   │   │   │   └── AuthController.php         # Login/Register handlers
│   │   │   ├── Client/
│   │   │   │   ├── BookingController.php      # Appointment booking flow
│   │   │   │   └── OnboardingController.php   # Profile completion
│   │   │   └── Counselor/
│   │   │       ├── CaseLogController.php      # Case log management
│   │   │       └── DashboardController.php    # Counselor dashboard
│   │   └── Middleware/
│   │       ├── RoleCheck.php                  # Role-based access control
│   │       └── VerifyDevice.php               # Device binding (TOFU)
│   └── Models/
│       ├── User.php                           # Central auth + profile
│       ├── CounselorProfile.php               # Counselor extension
│       ├── Appointment.php                    # Booking records
│       ├── CaseLog.php                        # Session documentation
│       ├── TreatmentGoal.php                  # Treatment planning
│       └── TreatmentActivity.php              # Goal activities
├── database/
│   └── migrations/
│       ├── 0001_01_01_000000_create_users_table.php
│       ├── 2024_12_14_000001_create_counselor_profiles_table.php
│       ├── 2024_12_14_000002_create_appointments_table.php
│       ├── 2024_12_14_000003_create_case_logs_table.php
│       ├── 2024_12_14_000004_create_treatment_goals_table.php
│       └── 2024_12_14_000005_create_treatment_activities_table.php
├── resources/views/
│   ├── auth/                    # Login/register pages
│   ├── client/                  # Student portal views
│   │   └── booking/             # Booking flow steps
│   ├── counselor/               # Counselor portal views
│   │   └── case-logs/           # Case log views
│   ├── admin/                   # Admin portal views
│   │   ├── counselors/          # Counselor management
│   │   └── clients/             # Client management
│   ├── layouts/                 # Base layouts
│   │   ├── app.blade.php        # Main layout
│   │   └── partials/
│   │       └── navbar.blade.php # Navigation
│   └── components/              # Reusable components
├── routes/
│   └── web.php                  # All route definitions
├── docs/
│   ├── AGENT_CONTEXT.md         # This file
│   └── PAGHUPAY_SPEC.md         # Full specification (move here)
└── storage/
    └── app/public/uploads/
        └── counselors/          # Counselor profile pictures
```

---

## 🔑 Route Architecture

### Route Groups & Middleware

| Prefix                | Middleware                                | Purpose               |
| --------------------- | ----------------------------------------- | --------------------- |
| `/login`, `/register` | `guest`                                   | Authentication pages  |
| `/`                   | `auth`, `role:client`                     | Client/Student portal |
| `/counselor`          | `auth`, `role:counselor`, `verify.device` | Counselor portal      |
| `/admin`              | `auth`, `role:admin`                      | Admin portal          |

### Key Routes

```php
// Guest Routes
GET  /login                         # Student login
GET  /counselor/login               # Counselor login
GET  /admin/login                   # Admin login
GET  /register                      # Student registration

// Client Routes (/)
GET  /                              # Welcome page
GET  /onboarding                    # Profile completion
GET  /booking/counselors            # Step 1: Choose counselor
GET  /booking/schedule/{counselor}  # Step 2: Pick date/time
GET  /booking/reason                # Step 3: Enter reason
POST /booking/store                 # Step 4: Submit booking
GET  /booking/confirmation          # Confirmation page
GET  /appointments                  # View appointments

// Counselor Routes (/counselor)
GET  /counselor/dashboard           # Dashboard
GET  /counselor/pending             # Pending appointments
GET  /counselor/today               # Today's appointments
POST /counselor/session/{id}/start  # Start session timer
POST /counselor/session/{id}/end    # End session timer
GET  /counselor/case-logs           # Case log list
POST /counselor/case-logs/{id}      # Create case log

// Admin Routes (/admin)
GET  /admin/dashboard               # Dashboard
GET  /admin/counselors              # Counselor list
POST /admin/counselors/{id}/reset-device  # Reset device lock
GET  /admin/clients                 # Client list
```

---

## 🗄️ Database Schema

### Users Table

Primary authentication table for all roles.

| Column            | Type    | Notes                            |
| ----------------- | ------- | -------------------------------- |
| `role`            | ENUM    | `admin`, `client`, `counselor`   |
| `is_active`       | BOOLEAN | `false` until profile completion |
| `temp_password`   | VARCHAR | For initial client setup         |
| Profile fields... | Various | Nullable until onboarding        |

### CounselorProfile Table

Extension for counselor-specific data.

| Column            | Type      | Notes                          |
| ----------------- | --------- | ------------------------------ |
| `device_token`    | VARCHAR   | SHA-256 hash, NULL on creation |
| `device_bound_at` | TIMESTAMP | When device was first bound    |
| `picture_url`     | VARCHAR   | Local storage path             |

### Appointments Table

| Column         | Type      | Notes                                                          |
| -------------- | --------- | -------------------------------------------------------------- |
| `status`       | VARCHAR   | `pending`, `accepted`, `rescheduled`, `cancelled`, `completed` |
| `scheduled_at` | TIMESTAMP | Combined date/time                                             |
| `reason`       | TEXT      | NOT encrypted (for filtering)                                  |

### CaseLog Table

| Column             | Type    | Notes                 |
| ------------------ | ------- | --------------------- |
| `case_log_id`      | VARCHAR | Format: `TUPV-{UUID}` |
| `progress_report`  | TEXT    | **🔐 ENCRYPTED**      |
| `additional_notes` | TEXT    | **🔐 ENCRYPTED**      |
| `session_duration` | INTEGER | Minutes               |

### TreatmentGoal / TreatmentActivity

| Column        | Type | Notes            |
| ------------- | ---- | ---------------- |
| `description` | TEXT | **🔐 ENCRYPTED** |

---

## 🔒 Security Implementation

### 1. Device Lock (Trust on First Use - TOFU)

**Middleware**: `app/Http/Middleware/VerifyDevice.php`

```
First Login → Generate SHA-256 token → Store in DB + Cookie (1 year)
Subsequent Logins → Validate cookie token against stored token
Mismatch → Logout + Error message
Admin Reset → Sets device_token to NULL
```

**Cookie Settings**:

-   `httpOnly: true` - XSS protection
-   `secure: true` - HTTPS only (production)
-   `sameSite: Lax` - CSRF protection

### 2. Role-Based Access Control

**Middleware**: `app/Http/Middleware/RoleCheck.php`

-   Validates user role against route requirements
-   Redirects to appropriate dashboard on mismatch

### 3. Data Encryption (AES-256-CBC)

**Encrypted Fields** (via Laravel's `encrypted` cast):

-   `case_logs.progress_report`
-   `case_logs.additional_notes`
-   `treatment_goals.description`
-   `treatment_activities.description`

**Important**: Encrypted fields CANNOT be searched with SQL `LIKE`. This is intentional for security.

---

## 📧 Email Configuration (SendGrid)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="guidance@tup.edu.ph"
```

---

## 📁 File Storage (Local)

Counselor profile pictures are stored locally (similar to multer in Node.js):

```php
// Upload
$path = $request->file('picture')->store('uploads/counselors', 'public');

// Access URL
Storage::disk('public')->url($path);
```

Storage location: `storage/app/public/uploads/counselors/`

Run `php artisan storage:link` to create public symlink.

---

## 🚀 Common Tasks

### Run Migrations

```bash
php artisan migrate
```

### Create Storage Link

```bash
php artisan storage:link
```

### Clear Caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Run Development Server

```bash
php artisan serve
```

---

## 📝 Development Notes

### When Adding New Features:

1. **New Model**: Create in `app/Models/`, add migration, update relationships
2. **New Controller**: Place in appropriate subdirectory (`Admin/`, `Client/`, `Counselor/`)
3. **New Views**: Follow existing directory structure
4. **New Routes**: Add to appropriate route group in `routes/web.php`

### When Modifying Security:

1. **Device Lock Changes**: Edit `VerifyDevice.php` middleware
2. **Role Changes**: Edit `RoleCheck.php` middleware
3. **Encryption Changes**: Update model `$casts` array

### When Working with Encrypted Data:

-   Data is automatically encrypted on save, decrypted on read
-   Never try to search encrypted fields with SQL
-   Use `APP_KEY` in `.env` for encryption key

---

## 📚 Reference Files

| File                    | Purpose                            |
| ----------------------- | ---------------------------------- |
| `docs/PAGHUPAY_SPEC.md` | Full system specification          |
| `.env.example`          | Environment configuration template |
| `routes/web.php`        | All route definitions              |
| `bootstrap/app.php`     | Middleware registration            |

---

## ⚠️ Important Constraints

1. **Local Intranet Only**: No internet dependencies in production
2. **PostgreSQL Required**: SQLite not supported
3. **Bootstrap 5**: No other CSS frameworks
4. **Blade Templates**: No Vue/React SPA
5. **Single Device per Counselor**: Security requirement
6. **Encrypted Sensitive Data**: Cannot be searched directly

---

_Last Updated: December 14, 2025_
