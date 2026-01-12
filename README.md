# Paghupay - TUP-V Guidance & Counseling System

A web-based Guidance and Counseling system for the Technological University of the Philippines - Visayas (TUP-V), deployed on local intranet.

## 🎯 Features

-   **Three User Roles**: Admin, Counselor, and Client (Student)
-   **Appointment Booking System**: Multi-step booking flow
-   **Case Log Management**: Encrypted session documentation
-   **Device Lock Security**: Trust-on-First-Use (TOFU) for counselor accounts
-   **Treatment Planning**: Goals and activities tracking

## 🛠️ Tech Stack

-   **Framework**: Laravel 12.x
-   **Frontend**: Blade Templates + Bootstrap 5
-   **Database**: PostgreSQL
-   **Email**: SendGrid (SMTP)
-   **File Storage**: Local

## 📋 Requirements

-   PHP 8.2+
-   Composer
-   PostgreSQL 14+
-   Node.js 18+ (for Vite)

## 🚀 Quick Start

### 1. Clone and Install Dependencies

```bash
cd paghupay
composer install
npm install
```

### 2. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your PostgreSQL credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=paghupay
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 3. Create Database

```sql
CREATE DATABASE paghupay;
```

### 4. Run Migrations & Seed

```bash
php artisan migrate
php artisan db:seed
```

### 5. Create Storage Link

```bash
php artisan storage:link
```

### 6. Start Development Server

```bash
php artisan serve
```

## 🔑 Default Credentials (After Seeding)

| Role      | Email                     | Password     |
| --------- | ------------------------- | ------------ |
| Admin     | admin@tupv.edu.ph         | admin123     |
| Counselor | maria.santos@tupv.edu.ph  | counselor123 |
| Student   | juan.delacruz@tupv.edu.ph | student123   |

## 📁 Project Structure

```
paghupay/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin portal controllers
│   │   ├── Auth/           # Authentication controllers
│   │   ├── Client/         # Student portal controllers
│   │   └── Counselor/      # Counselor portal controllers
│   ├── Http/Middleware/
│   │   ├── RoleCheck.php       # Role-based access control
│   │   └── VerifyDevice.php    # Device binding (TOFU)
│   └── Models/             # Eloquent models
├── database/
│   └── migrations/         # Database schema
├── resources/views/        # Blade templates
├── routes/
│   └── web.php            # Route definitions
└── docs/
    ├── AGENT_CONTEXT.md   # AI agent reference
    └── PAGHUPAY_SPEC.md   # Full specification
```

## 🔒 Security Features

1. **Device Lock (TOFU)**: Counselor accounts are bound to a single device
2. **Role-Based Access**: Strict route segregation by user role
3. **Data Encryption**: Sensitive case log data encrypted at rest (AES-256)
4. **Secure Cookies**: HttpOnly, Secure, SameSite protections

## 📧 Email Configuration (SendGrid)

Update `.env` with your SendGrid API key:

```env
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
```

## 📝 Documentation

-   Full specification: [docs/PAGHUPAY_SPEC.md](docs/PAGHUPAY_SPEC.md)
-   AI agent context: [docs/AGENT_CONTEXT.md](docs/AGENT_CONTEXT.md)

## 📜 License

Proprietary - TUP-V Internal Use Only

---

_Developed for TUP-V Guidance & Counseling Office_
