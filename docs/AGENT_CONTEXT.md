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
│   │   │   │   ├── BookingController.php      # Appointment booking flow (4-step)
│   │   │   │   └── OnboardingController.php   # Profile completion
│   │   │   └── Counselor/
│   │   │       ├── CaseLogController.php      # Case log management
│   │   │       └── DashboardController.php    # Counselor dashboard
│   │   └── Middleware/
│   │       ├── RoleCheck.php                  # Role-based access control
│   │       └── VerifyDevice.php               # Device binding (TOFU)
│   ├── Mail/
│   │   ├── StudentInvitation.php              # Student invite email
│   │   └── AppointmentConfirmation.php        # Booking confirmation email
│   └── Models/
│       ├── User.php                           # Central auth + profile
│       ├── CounselorProfile.php               # Counselor extension
│       ├── Appointment.php                    # Booking records
│       ├── CaseLog.php                        # Session documentation
│       ├── TreatmentGoal.php                  # Treatment planning
│       ├── TreatmentActivity.php              # Goal activities
│       ├── TimeSlot.php                       # Available booking time slots
│       └── BlockedDate.php                    # Dates blocked for booking
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2024_12_14_000001_create_counselor_profiles_table.php
│   │   ├── 2024_12_14_000002_create_appointments_table.php
│   │   ├── 2024_12_14_000003_create_case_logs_table.php
│   │   ├── 2024_12_14_000004_create_treatment_goals_table.php
│   │   ├── 2024_12_14_000005_create_treatment_activities_table.php
│   │   ├── 2024_12_16_000001_create_time_slots_table.php
│   │   └── 2024_12_16_000002_create_blocked_dates_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php                 # Main seeder
│       └── TimeSlotSeeder.php                 # Seeds time slots
├── resources/views/
│   ├── auth/                    # Login/register pages
│   ├── client/                  # Student portal views
│   │   └── booking/             # Booking flow steps
│   │       ├── index.blade.php        # Booking start page
│   │       ├── choose-counselor.blade.php  # Step 1
│   │       ├── schedule.blade.php     # Step 2: Date & time
│   │       ├── reason.blade.php       # Step 3: Reason input
│   │       └── thankyou.blade.php     # Confirmation page
│   ├── counselor/               # Counselor portal views
│   │   ├── dashboard.blade.php  # Dashboard with stats
│   │   ├── appointments/        # Appointments management
│   │   │   └── index.blade.php  # 3-tab view (Pending, Calendar, Day)
│   │   ├── case-logs/           # Case log views
│   │   │   ├── index.blade.php  # List with search
│   │   │   ├── create.blade.php # Create form
│   │   │   ├── show.blade.php   # Detail view
│   │   │   ├── edit.blade.php   # Edit form
│   │   │   └── pdf.blade.php    # PDF export template
│   │   └── about.blade.php      # About page
│   ├── admin/                   # Admin portal views
│   │   ├── counselors/          # Counselor management
│   │   └── clients/             # Client management
│   ├── emails/                  # Email templates
│   │   ├── student-invitation.blade.php
│   │   └── appointment-confirmation.blade.php
│   ├── layouts/                 # Base layouts
│   │   ├── app.blade.php        # Main layout
│   │   ├── counselor.blade.php  # Counselor sidebar layout
│   │   └── partials/
│   │       └── navbar.blade.php # Navigation
│   └── components/              # Reusable components
├── routes/
│   └── web.php                  # All route definitions
├── docs/
│   ├── AGENT_CONTEXT.md         # This file
│   └── PAGHUPAY_SPEC.md         # Full specification
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

// Booking Flow Routes (/booking)
GET  /booking                       # Booking start page
GET  /booking/counselors            # Step 1: Choose counselor
POST /booking/counselors            # Store counselor selection
GET  /booking/schedule/{counselor}  # Step 2: Pick date/time
POST /booking/schedule/{counselor}  # Store schedule selection
GET  /booking/counselor/{id}/slots  # API: Get available slots for date
GET  /booking/reason                # Step 3: Enter reason
POST /booking/store                 # Submit booking
GET  /booking/thankyou              # Thank you / confirmation page
GET  /appointments                  # View appointments

// Counselor Routes (/counselor)
GET  /counselor/dashboard                      # Dashboard with stats
GET  /counselor/appointments                   # Appointments management (3-tab view)
POST /counselor/appointments/{id}/accept       # Accept pending appointment
POST /counselor/appointments/{id}/cancel       # Cancel appointment
POST /counselor/appointments/{id}/start-session # Start session timer
POST /counselor/appointments/{id}/end-session  # End session, create case log
GET  /counselor/appointments/active-session    # Get current active session
GET  /counselor/case-logs                      # Case log list with search/stats
GET  /counselor/case-logs/create               # Create new case log
POST /counselor/case-logs/store                # Save new case log
GET  /counselor/case-logs/{id}                 # View case log details
GET  /counselor/case-logs/{id}/edit            # Edit case log
PUT  /counselor/case-logs/{id}                 # Update case log
DELETE /counselor/case-logs/{id}               # Delete case log
GET  /counselor/case-logs/{id}/export-pdf      # Export case log to PDF
GET  /counselor/about                          # About page
GET  /counselor/clients/{id}/history           # Client appointment history

// Admin Routes (/admin)
GET  /admin/dashboard               # Dashboard
GET  /admin/counselors              # Counselor list
POST /admin/counselors/{id}/reset-device  # Reset device lock
GET  /admin/clients                 # Client management (count + add)
POST /admin/clients                 # Create new client (email only)
```

---

## 🗄️ Database Schema

### Users Table

Primary authentication table for all roles.

| Column            | Type    | Notes                            |
| ----------------- | ------- | -------------------------------- |
| `role`            | ENUM    | `admin`, `client`, `counselor`   |
| `is_active`       | BOOLEAN | `false` until profile completion |
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

### TimeSlot Table (NEW)

Available booking time slots.

| Column       | Type    | Notes                    |
| ------------ | ------- | ------------------------ |
| `type`       | ENUM    | `morning` or `afternoon` |
| `start_time` | TIME    | Slot start (e.g., 09:00) |
| `end_time`   | TIME    | Slot end (e.g., 10:30)   |
| `is_active`  | BOOLEAN | Can be disabled by admin |

**Default Slots:**

-   Morning: 9:00-10:30, 10:30-12:00
-   Afternoon: 1:00-2:30, 2:30-4:00

### BlockedDate Table (NEW)

Dates when booking is unavailable.

| Column         | Type    | Notes                             |
| -------------- | ------- | --------------------------------- |
| `blocked_date` | DATE    | Unique, the blocked date          |
| `reason`       | VARCHAR | Optional reason (e.g., "Holiday") |
| `created_by`   | BIGINT  | FK to users (admin)               |

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

## � Authentication System (IMPLEMENTED)

The authentication system is the central feature of Paghupay, handling three distinct user roles with separate login flows.

### Authentication Overview

| Role      | Login URL          | View File                        | Post-Login Redirect            |
| --------- | ------------------ | -------------------------------- | ------------------------------ |
| Client    | `/login`           | `auth/login.blade.php`           | `/` (Welcome) or `/onboarding` |
| Counselor | `/counselor/login` | `auth/counselor-login.blade.php` | `/counselor/dashboard`         |
| Admin     | `/admin/login`     | `auth/admin-login.blade.php`     | `/admin/dashboard`             |

### Key Files

```
app/Http/Controllers/Auth/AuthController.php    # All auth handlers
app/Http/Middleware/RoleCheck.php               # Role validation
app/Http/Middleware/VerifyDevice.php            # Counselor device lock
bootstrap/app.php                               # Middleware aliases
resources/views/auth/
├── login.blade.php                             # Student login (blue theme)
├── counselor-login.blade.php                   # Counselor login (green theme)
├── admin-login.blade.php                       # Admin login (red theme)
└── register.blade.php                          # Student registration
```

### AuthController Methods

| Method                     | Route                   | Purpose                           |
| -------------------------- | ----------------------- | --------------------------------- |
| `showLoginForm()`          | GET `/login`            | Display student login form        |
| `login()`                  | POST `/login`           | Handle student authentication     |
| `showCounselorLoginForm()` | GET `/counselor/login`  | Display counselor login form      |
| `counselorLogin()`         | POST `/counselor/login` | Handle counselor authentication   |
| `showAdminLoginForm()`     | GET `/admin/login`      | Display admin login form          |
| `adminLogin()`             | POST `/admin/login`     | Handle admin authentication       |
| `showRegistrationForm()`   | GET `/register`         | Display student registration form |
| `register()`               | POST `/register`        | Create new student account        |
| `logout()`                 | POST `/logout`          | Log out any authenticated user    |

### Login Flow Logic

```php
// Each role-specific login validates the role after authentication:
if (Auth::attempt($credentials)) {
    $user = Auth::user();

    // Ensure user has correct role for this login page
    if ($user->role !== 'expected_role') {
        Auth::logout();
        return back()->withErrors([
            'email' => 'Please use the appropriate login page for your role.',
        ]);
    }

    $request->session()->regenerate();
    return redirect()->intended(route('role.dashboard'));
}
```

### Student Registration Flow

1. User visits `/register`
2. Fills in: Name, Email, Password, Password Confirmation
3. Must accept Data Privacy Agreement (RA 10173)
4. Account created with `is_active = false`
5. Redirected to `/onboarding` to complete profile
6. After onboarding, `is_active = true`

### Middleware Stack

```php
// Guest routes (unauthenticated only)
Route::middleware('guest')->group(function () {
    // All login and register routes
});

// Client routes
Route::middleware(['auth', 'role:client'])->group(function () {
    // Student portal routes
});

// Counselor routes (includes device verification)
Route::middleware(['auth', 'role:counselor', 'verify.device'])->group(function () {
    // Counselor portal routes
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin portal routes
});
```

### RoleCheck Middleware Behavior

When a user tries to access a route for a different role:

-   **Admin** → Redirected to `/admin/dashboard`
-   **Counselor** → Redirected to `/counselor/dashboard`
-   **Client** → Redirected to `/` (welcome page)

### VerifyDevice Middleware (Counselors Only)

Implements **Trust on First Use (TOFU)** device binding:

```
1. First Login (device_token is NULL):
   → Generate SHA-256 token from: uniqid + userAgent + IP
   → Store token in DB (counselor_profiles.device_token)
   → Set browser cookie (1 year expiry, httpOnly, secure)
   → Allow access

2. Subsequent Logins:
   → Compare cookie token with stored DB token
   → Match → Allow access
   → Mismatch → Logout + Error message

3. Device Reset (Admin action):
   → Sets device_token to NULL
   → Next login will bind new device
```

### View Themes by Role

Each login page has a distinct color theme for quick visual identification:

-   **Student**: Blue (`btn-primary`, `bg-primary`)
-   **Counselor**: Green (`btn-success`, `bg-success`)
-   **Admin**: Red (`btn-danger`, `bg-danger`)

### Common Auth Helpers in Blade

```blade
{{-- Check authentication --}}
@auth
    <p>Welcome, {{ auth()->user()->name }}</p>
@endauth

@guest
    <a href="{{ route('login') }}">Login</a>
@endguest

{{-- Check specific role --}}
@if(auth()->user()->role === 'admin')
    {{-- Admin-only content --}}
@endif

{{-- Logout form --}}
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Logout</button>
</form>
```

### Creating Test Users

```php
// In DatabaseSeeder or Tinker
use App\Models\User;
use App\Models\CounselorProfile;

// Admin
User::create([
    'name' => 'Admin User',
    'email' => 'admin@tup.edu.ph',
    'password' => bcrypt('password'),
    'role' => 'admin',
    'is_active' => true,
]);

// Client (Student)
User::create([
    'name' => 'Student User',
    'email' => 'student@tup.edu.ph',
    'password' => bcrypt('password'),
    'role' => 'client',
    'is_active' => true,
]);

// Counselor (requires profile)
$counselor = User::create([
    'name' => 'Dr. Maria Santos',
    'email' => 'counselor@tup.edu.ph',
    'password' => bcrypt('password'),
    'role' => 'counselor',
    'is_active' => true,
]);
CounselorProfile::create([
    'user_id' => $counselor->id,
    'position' => 'Head Psychologist',
]);
```

---

## 📅 Client Booking Flow (IMPLEMENTED)

The complete 4-step booking flow for students to schedule appointments with counselors.

### Booking Overview

| Step | Route                           | View                              | Purpose                |
| ---- | ------------------------------- | --------------------------------- | ---------------------- |
| 0    | `/booking`                      | `client/booking/index`            | Start booking page     |
| 1    | `/booking/counselors`           | `client/booking/choose-counselor` | Select counselor       |
| 2    | `/booking/schedule/{counselor}` | `client/booking/schedule`         | Pick date & time slot  |
| 3    | `/booking/reason`               | `client/booking/reason`           | Enter reason for visit |
| 4    | `/booking/thankyou`             | `client/booking/thankyou`         | Confirmation page      |

### Key Files

```
app/Http/Controllers/Client/BookingController.php  # All booking logic
app/Models/TimeSlot.php                            # Time slot model
app/Models/BlockedDate.php                         # Blocked dates model
app/Mail/AppointmentConfirmation.php               # Confirmation email
resources/views/client/booking/
├── index.blade.php              # Start page with "Book Now" button
├── choose-counselor.blade.php   # Counselor cards with selection
├── schedule.blade.php           # Date picker + time slot selection
├── reason.blade.php             # Textarea for reason + summary
└── thankyou.blade.php           # Success message + next steps
resources/views/emails/
└── appointment-confirmation.blade.php  # Email template
```

### BookingController Methods

| Method                | Route                               | Purpose                    |
| --------------------- | ----------------------------------- | -------------------------- |
| `index()`             | GET `/booking`                      | Show booking start page    |
| `chooseCounselor()`   | GET `/booking/counselors`           | Display counselor list     |
| `selectCounselor()`   | POST `/booking/counselors`          | Store selected counselor   |
| `schedule()`          | GET `/booking/schedule/{id}`        | Show date/time selection   |
| `selectSchedule()`    | POST `/booking/schedule/{id}`       | Store selected schedule    |
| `getAvailableSlots()` | GET `/booking/counselor/{id}/slots` | API: Get slots for date    |
| `reason()`            | GET `/booking/reason`               | Show reason input form     |
| `store()`             | POST `/booking/store`               | Submit booking, send email |
| `thankyou()`          | GET `/booking/thankyou`             | Show confirmation page     |

### Booking Rules

1. **Weekends Disabled**: No Saturday/Sunday bookings
2. **Blocked Dates**: Admin-blocked dates unavailable
3. **Slot Availability**: Real-time check via AJAX
4. **No Double Booking**: One slot per counselor per time

### Session Data During Booking

```php
session('booking.counselor_id')    // Selected counselor
session('booking.scheduled_date')  // Selected date (Y-m-d)
session('booking.time_slot_id')    // Selected time slot
session('last_appointment_id')     // For thank you page
```

### Time Slot API Response

```json
GET /booking/counselor/{id}/slots?date=2024-12-20

{
  "morning": [
    {"id": 1, "type": "morning", "formatted_time": "9:00 AM - 10:30 AM", "is_available": true},
    {"id": 2, "type": "morning", "formatted_time": "10:30 AM - 12:00 PM", "is_available": false}
  ],
  "afternoon": [
    {"id": 3, "type": "afternoon", "formatted_time": "1:00 PM - 2:30 PM", "is_available": true},
    {"id": 4, "type": "afternoon", "formatted_time": "2:30 PM - 4:00 PM", "is_available": true}
  ]
}
```

### Email Notification

Sent automatically after booking via SendGrid:

-   Template: `resources/views/emails/appointment-confirmation.blade.php`
-   Includes: Counselor name, date, time, status (pending), next steps
-   Tracks delivery: `appointments.email_sent` flag

---

## 🧑‍⚕️ Counselor Dashboard (IMPLEMENTED)

The complete counselor portal with sidebar navigation, dashboard, appointments management, and case logs.

### Dashboard Overview

| Section      | Route                     | View                           | Purpose                         |
| ------------ | ------------------------- | ------------------------------ | ------------------------------- |
| Dashboard    | `/counselor/dashboard`    | `counselor/dashboard`          | Stats overview                  |
| Appointments | `/counselor/appointments` | `counselor/appointments/index` | Calendar view, pending requests |
| Case Logs    | `/counselor/case-logs`    | `counselor/case-logs/index`    | List all case logs              |
| About        | `/counselor/about`        | `counselor/about`              | About the application           |

### Key Files

```
app/Http/Controllers/Counselor/
├── DashboardController.php          # Dashboard with stats
├── AppointmentController.php        # Appointments + calendar logic
└── CaseLogController.php            # Case logs CRUD + PDF export
resources/views/layouts/
└── counselor.blade.php              # Sidebar layout (profile, nav menu)
resources/views/counselor/
├── dashboard.blade.php              # Stats cards
├── appointments/
│   └── index.blade.php              # 3 tabs: Pending, Calendar, Day View
├── case-logs/
│   ├── index.blade.php              # List with search, stats
│   ├── create.blade.php             # Create form with student selection
│   ├── show.blade.php               # Detail view
│   ├── edit.blade.php               # Edit form with treatment plan
│   └── pdf.blade.php                # PDF export template
└── about.blade.php                  # About page
```

### Layout: Sidebar Navigation

**File**: `resources/views/layouts/counselor.blade.php`

Features:

-   Profile dropdown with logout
-   Navigation menu: Dashboard, Appointments (with pending badge), Case Logs, About Us
-   Active state highlighting
-   Responsive mobile support

### DashboardController Methods

| Method    | Route                      | Purpose                                             |
| --------- | -------------------------- | --------------------------------------------------- |
| `index()` | GET `/counselor/dashboard` | Show stats: pending requests, today's, this month's |

**Stats Displayed**:

-   Pending Appointment Requests count
-   Today's Appointments count
-   This Month's Appointments count

### AppointmentController Methods (Enhanced)

| Method            | Route                                             | Purpose                           |
| ----------------- | ------------------------------------------------- | --------------------------------- |
| `index()`         | GET `/counselor/appointments`                     | Show appointments with 3-tab view |
| `accept()`        | POST `/counselor/appointments/{id}/accept`        | Accept pending appointment        |
| `cancel()`        | POST `/counselor/appointments/{id}/cancel`        | Cancel appointment                |
| `startSession()`  | POST `/counselor/appointments/{id}/start-session` | Start session timer               |
| `endSession()`    | POST `/counselor/appointments/{id}/end-session`   | End session, create case log      |
| `activeSession()` | GET `/counselor/appointments/active-session`      | Get current active session        |

**Appointments View Features**:

-   **Pending Requests Tab**: List of pending appointments with Accept/Decline buttons
-   **Calendar View Tab**: Full month calendar with dots showing appointments per day
-   **Day View Tab**: List of appointments for selected day with Start Session/Cancel buttons

**Calendar Building Logic**:

```php
// Builds 7-column calendar grid (Sun-Sat)
// Shows dots for days with appointments
// Color coding: Today (blue), Selected (green), Has appointments (dots)
```

### CaseLogController Methods (Enhanced)

| Method        | Route                                      | Purpose                               |
| ------------- | ------------------------------------------ | ------------------------------------- |
| `index()`     | GET `/counselor/case-logs`                 | List with stats, search functionality |
| `create()`    | GET `/counselor/case-logs/create`          | Create form with student selection    |
| `store()`     | POST `/counselor/case-logs/store`          | Save new case log                     |
| `show()`      | GET `/counselor/case-logs/{id}`            | View case log details                 |
| `edit()`      | GET `/counselor/case-logs/{id}/edit`       | Edit case log                         |
| `update()`    | PUT `/counselor/case-logs/{id}`            | Update case log                       |
| `destroy()`   | DELETE `/counselor/case-logs/{id}`         | Delete case log                       |
| `exportPdf()` | GET `/counselor/case-logs/{id}/export-pdf` | Export case log to PDF                |

**Case Logs Index Features**:

-   Stats cards: Total logs, This month logs, Average duration
-   Search functionality by student name/TUPV ID
-   Table with columns: Created Date, TUPV ID, Log #, Duration, Actions
-   Actions: View, Edit, Export PDF, Delete

**Case Log Create/Edit Features**:

-   Student selection dropdown (for create)
-   Date and duration fields
-   Progress report textarea
-   Additional notes textarea
-   Dynamic Treatment Plan section:
    -   Add/remove goals
    -   Add/remove activities per goal
    -   Activity date picker

**PDF Export**:

-   Clean printable layout
-   Includes all case log details
-   Treatment goals and activities formatted
-   Auto-prints on page load

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

_Last Updated: January 9, 2026 (Counselor Dashboard Implemented)_
