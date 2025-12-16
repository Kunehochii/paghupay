# **Spec-Driven Development: TUP-V Guidance & Counseling System**

Version: 1.8 (Client Booking Flow Implemented)

## **Implementation Status**

| Feature             | Status         | Notes                                                        |
| :------------------ | :------------- | :----------------------------------------------------------- |
| Database Schema     | ✅ Implemented | All migrations created (including time_slots, blocked_dates) |
| Authentication      | ✅ Implemented | All login pages, registration, middleware                    |
| Device Lock (TOFU)  | ✅ Implemented | VerifyDevice middleware complete                             |
| Role-Based Access   | ✅ Implemented | RoleCheck middleware complete                                |
| Client Booking Flow | ✅ Implemented | Full 4-step booking with email confirmation                  |
| Time Slots System   | ✅ Implemented | Morning/Afternoon slots with availability check              |
| Blocked Dates       | ✅ Implemented | Admin can block dates, weekends auto-disabled                |
| Counselor Dashboard | ⏳ Pending     |                                                              |
| Admin Management    | ⏳ Pending     |                                                              |
| Data Encryption     | ⏳ Pending     | Model casts defined, needs testing                           |
| Email Notifications | ✅ Implemented | Appointment confirmation email via SendGrid                  |

---

1. **Context:** This is a web-based Guidance and Counseling system deployed on a **Local Intranet (TUPV WiFi)**. It serves three distinct user roles: Admin, Client (Student/Staff), and Counselor.
2. **Tech Stack Constraints:**
    - **Framework:** Laravel 12.x (PHP 8.2+)
    - **Frontend:** Blade Templates \+ Bootstrap 5
    - **Database:** PostgreSQL (No SQLite/MySQL)
    - **Email:** SendGrid (SMTP)
    - **File Storage:** Local (storage/app/public) - No cloud storage
    - **Caching/Sessions/Queue:** Database-driven (No Redis)
    - **Deployment:** Local Server (No Cloud/Internet dependencies)
3. **Route Separation:**
    - guest: Login pages.
    - / (Root): Client/Student Portal (auth, role:client).
    - /counselor: Counselor Portal (auth, role:counselor, verify.device).
    - /admin: Admin Portal (auth, role:admin).

## **1\. Global Data Schema (PostgreSQL)**

### **1.1 Users Table (users)**

Centralized table for authentication.

| Column                | Type      | Nullable | Description                            |
| :-------------------- | :-------- | :------- | :------------------------------------- |
| id                    | BIGSERIAL | NO       | Primary Key                            |
| name                  | VARCHAR   | NO       | Full Name                              |
| email                 | VARCHAR   | NO       | Unique, Auth Identifier                |
| password              | VARCHAR   | NO       | Hashed (Bcrypt)                        |
| role                  | ENUM      | NO       | admin, client, counselor               |
| nickname              | VARCHAR   | YES      | Preferred name                         |
| course_year_section   | VARCHAR   | YES      | e.g., “BSIT-4A”                        |
| birthdate             | DATE      | YES      |                                        |
| birthplace            | VARCHAR   | YES      |                                        |
| sex                   | VARCHAR   | YES      | Male, Female                           |
| contact_number        | VARCHAR   | YES      |                                        |
| fb_account            | VARCHAR   | YES      | Facebook Profile Link/Name             |
| nationality           | VARCHAR   | YES      |                                        |
| address               | TEXT      | YES      | Current Address                        |
| home_address          | TEXT      | YES      | Permanent Address                      |
| guardian_name         | VARCHAR   | YES      |                                        |
| guardian_relationship | VARCHAR   | YES      |                                        |
| guardian_contact      | VARCHAR   | YES      |                                        |
| is_active             | BOOLEAN   | NO       | Default false until profile completion |
| created_at            | TIMESTAMP | NO       |                                        |

### **1.2 Counselor Profiles Table (counselor_profiles)**

Extension table for users with role: counselor.

| Column          | Type      | Nullable | Description                                                          |
| :-------------- | :-------- | :------- | :------------------------------------------------------------------- |
| id              | BIGSERIAL | NO       | Primary Key                                                          |
| user_id         | BIGINT    | NO       | FK \-\> users.id                                                     |
| position        | VARCHAR   | YES      | e.g., “Head Psychologist”                                            |
| picture_url     | VARCHAR   | YES      | Path to uploaded image                                               |
| temp_password   | VARCHAR   | YES      | For initial setup                                                    |
| device_token    | VARCHAR   | YES      | **Security Lock**. SHA-256 hash. Null on creation, set on 1st login. |
| device_bound_at | TIMESTAMP | YES      | When the device was first bound                                      |

### **1.3 Appointments Table (appointments)**

| Column       | Type      | Nullable | Description                                                |
| :----------- | :-------- | :------- | :--------------------------------------------------------- |
| id           | BIGSERIAL | NO       | Primary Key                                                |
| client_id    | BIGINT    | NO       | FK \-\> users.id                                           |
| counselor_id | BIGINT    | NO       | FK \-\> users.id                                           |
| status       | VARCHAR   | NO       | pending, accepted, rescheduled, cancelled, completed       |
| scheduled_at | TIMESTAMP | NO       | Combined Date and Time                                     |
| reason       | TEXT      | NO       | Brief description (Not encrypted to allow basic filtering) |
| email_sent   | BOOLEAN   | NO       | Default false (Tracks if notification was sent)            |
| created_at   | TIMESTAMP | NO       |                                                            |

### **1.4 Case Logs Table (case_logs)**

**Security Note:** Fields marked **\[Encrypted\]** must use application-level encryption (AES-256).

| Column           | Type      | Nullable | Description                       |
| :--------------- | :-------- | :------- | :-------------------------------- |
| id               | BIGSERIAL | NO       | Primary Key                       |
| case_log_id      | VARCHAR   | NO       | Format: TUPV-{UUID}               |
| appointment_id   | BIGINT    | NO       | FK \-\> appointments.id           |
| counselor_id     | BIGINT    | NO       | FK \-\> users.id                  |
| client_id        | BIGINT    | NO       | FK \-\> users.id                  |
| start_time       | TIMESTAMP | YES      | When session started              |
| end_time         | TIMESTAMP | YES      | When session ended                |
| session_duration | INTEGER   | YES      | Minutes                           |
| progress_report  | TEXT      | YES      | **\[Encrypted\]** Session notes   |
| additional_notes | TEXT      | YES      | **\[Encrypted\]** Recommendations |
| created_at       | TIMESTAMP | NO       |                                   |

### **1.5 Treatment Goals Table (treatment_goals)**

| Column      | Type      | Description                                 |
| :---------- | :-------- | :------------------------------------------ |
| id          | BIGSERIAL | Primary Key                                 |
| case_log_id | BIGINT    | FK \-\> case_logs.id                        |
| description | TEXT      | **\[Encrypted\]** The main goal description |

### **1.6 Treatment Activities Table (treatment_activities)**

| Column        | Type      | Description                         |
| :------------ | :-------- | :---------------------------------- |
| id            | BIGSERIAL | Primary Key                         |
| goal_id       | BIGINT    | FK \-\> treatment_goals.id          |
| description   | TEXT      | **\[Encrypted\]** Specific activity |
| activity_date | DATE      | When this activity is set for       |

### **1.7 Time Slots Table (time_slots)** ✅ NEW

Defines available appointment time slots.

| Column     | Type      | Nullable | Description                            |
| :--------- | :-------- | :------- | :------------------------------------- |
| id         | BIGSERIAL | NO       | Primary Key                            |
| type       | ENUM      | NO       | `morning` or `afternoon`               |
| start_time | TIME      | NO       | Slot start time (e.g., 09:00:00)       |
| end_time   | TIME      | NO       | Slot end time (e.g., 10:30:00)         |
| is_active  | BOOLEAN   | NO       | Default true, can be disabled by admin |
| created_at | TIMESTAMP | NO       |                                        |

**Seeded Data:**
| Type | Start Time | End Time |
| :-------- | :--------- | :------- |
| morning | 09:00 | 10:30 |
| morning | 10:30 | 12:00 |
| afternoon | 13:00 | 14:30 |
| afternoon | 14:30 | 16:00 |

### **1.8 Blocked Dates Table (blocked_dates)** ✅ NEW

Dates when appointments cannot be booked.

| Column       | Type      | Nullable | Description                           |
| :----------- | :-------- | :------- | :------------------------------------ |
| id           | BIGSERIAL | NO       | Primary Key                           |
| blocked_date | DATE      | NO       | The date that is blocked (unique)     |
| reason       | VARCHAR   | YES      | Reason for blocking (e.g., "Holiday") |
| created_by   | BIGINT    | YES      | FK \-\> users.id (admin who blocked)  |
| created_at   | TIMESTAMP | NO       |                                       |

## **2\. Authentication & Login Pages** ✅ IMPLEMENTED

**Distinct Login Pages Required:**

1. **Student Login:** /login (Default) ✅
2. **Counselor Login:** /counselor/login ✅
3. **Admin Login:** /admin/login ✅
4. **Student Registration:** /register ✅

**Implementation Files:**

-   Controller: `app/Http/Controllers/Auth/AuthController.php`
-   Views: `resources/views/auth/login.blade.php`, `counselor-login.blade.php`, `admin-login.blade.php`, `register.blade.php`
-   Middleware: `app/Http/Middleware/RoleCheck.php`, `app/Http/Middleware/VerifyDevice.php`
-   Routes: `routes/web.php` (guest middleware group)

## **3\. Client (Student) Portal Flows**

### **3.1 Registration & Onboarding**

**Pre-requisite:** Admin creates student account with email only → System generates temp password (hashed) → Plain temp password emailed to student.

**Security Note:** Temporary password is NOT stored in plain text. It's hashed in the `password` field and only exists in the email sent to the student.

**Registration Flow:**

1. Student receives email with temporary password
2. Student visits `/login` and logs in with temp password
3. System detects `is_active = false` → Redirects to `/register` (Change Password + Profile)
4. Student enters:
    - **Current Password** (temporary password from email)
    - **New Password**
    - **Confirm New Password**
    - **All Profile Fields** (see below)
5. System validates current password matches stored hash
6. On success → Password updated, profile saved, `is_active = true`
7. Redirect to **Student Welcome Page**

**Profile Completion Form Fields:**
| Field | Type | Required | Notes |
| :-------------------- | :------- | :------- | :------------------------------ |
| nickname | TEXT | YES | Preferred name |
| course_year_section | TEXT | YES | e.g., "BSIT-4A" |
| birthdate | DATE | YES | Date picker |
| birthplace | TEXT | YES | |
| sex | SELECT | YES | Male / Female |
| contact_number | TEXT | YES | Mobile number |
| fb_account | TEXT | NO | Facebook Profile Link/Name |
| nationality | TEXT | YES | Default: "Filipino" |
| address | TEXTAREA | YES | Current Address |
| home_address | TEXTAREA | YES | Permanent Address |
| guardian_name | TEXT | YES | |
| guardian_relationship | TEXT | YES | e.g., "Mother", "Father" |
| guardian_contact | TEXT | YES | Guardian's contact number |

**Terms Agreement:**

-   Checkbox: "I agree to the Data Privacy Act (RA 10173)"
-   Must be checked to proceed

**Outcome:**

-   `users.password` updated to new hashed password (replaces temp password hash)
-   `users.is_active` set to true
-   `users.name` set to full name from form
-   All profile fields populated
-   Redirect to **Student Welcome Page**

### **3.2 Booking Flow (Complete Cycle)** ✅ IMPLEMENTED

**Implementation Files:**

-   Controller: `app/Http/Controllers/Client/BookingController.php`
-   Views: `resources/views/client/booking/` (index, choose-counselor, schedule, reason, thankyou)
-   Mail: `app/Mail/AppointmentConfirmation.php`
-   Email Template: `resources/views/emails/appointment-confirmation.blade.php`

**Flow Overview:**

-   **Start:** **Student Welcome Page** (`/`) → Click "Start Booking"
-   **Step 1:** **Booking Start Page** (`/booking`) → Click "Start"
-   **Step 2:** **Choose Counselor Page** (`/booking/counselors`) → Select counselor card → Click "Next"
-   **Step 3:** **Schedule Page** (`/booking/schedule/{counselor}`) → Select date & time slot → Click "Next"
-   **Step 4:** **Reason Page** (`/booking/reason`) → Enter reason → Click "Submit Booking"
-   **Step 5:** **Thank You Page** (`/booking/thankyou`) → Confirmation with details

**Booking Rules:**

1. **Weekends Disabled:** Students cannot book on Saturdays or Sundays
2. **Blocked Dates:** Admin can block specific dates (holidays, events)
3. **Time Slot Availability:** Each counselor has limited slots per day
4. **No Double Booking:** Same counselor cannot have two appointments at the same time

**Time Slot System:**

-   Slots are defined in `time_slots` table
-   Split between morning and afternoon
-   Each slot shows availability status
-   API endpoint `/booking/counselor/{id}/slots?date=YYYY-MM-DD` returns real-time availability

**Session Storage During Booking:**

```php
session('booking.counselor_id')    // Selected counselor ID
session('booking.scheduled_date')  // Selected date (Y-m-d)
session('booking.time_slot_id')    // Selected time slot ID
```

**Email Notification:**

-   Sent immediately after successful booking via SendGrid
-   Template includes: appointment details, status (pending), next steps
-   `appointments.email_sent` flag tracks delivery status

**Routes:**

```php
GET  /booking                           # Start page
GET  /booking/counselors                # Step 1: Choose counselor
POST /booking/counselors                # Store counselor selection
GET  /booking/schedule/{counselor}      # Step 2: Date & time
POST /booking/schedule/{counselor}      # Store schedule selection
GET  /booking/counselor/{id}/slots      # API: Available slots for date
GET  /booking/reason                    # Step 3: Enter reason
POST /booking/store                     # Submit booking
GET  /booking/thankyou                  # Confirmation page
```

## **4\. Counselor Portal Flows**

**Security:** Middleware verify.device checks the device_token column in counselor_profiles and validates against browser cookie.

### **4.1 Dashboard & Pending View**

-   **Landing:** **Counselor Dashboard**.
-   **Pending Page:**
    -   **Visual Calendar:** Dates marked with dots (color-coded).
    -   **List View:** Side-by-side or below calendar.
-   **Today’s Actions:**
    -   **Card:** “Today’s Appointments” \-\> **Today’s List View**.

### **4.2 Live Session Flow**

-   **View:** **Today’s List View**.
-   **Action 1 (Start):** Click Start Session. System starts JS Timer.
-   **Action 2 (End):** Click End Session. System saves duration \-\> Redirects to **Case Log Page**.

### **4.3 Case Logs & Treatment Plan**

-   **Page:** **Case Log Entry**.
-   **Fields:** case_log_id, progress_report, additional_notes.
-   **Treatment Plan:** Dynamic Form (Goals \+ Activities).
-   **Action:** Click Save \-\> Returns to **Dashboard**.

## **5\. Admin Portal Flows**

### **5.1 Counselor Management**

-   **Flow:** Dashboard \-\> **Counselors Card** \-\> **Counselor List**.
-   **Add Counselor:**
    -   **Form:** Name, Email, Position, Photo upload.
    -   **System:** Creates User (Role: Counselor) \+ Counselor Profile (device_token: NULL).
-   **Counselor List View:**
    -   **Table Columns:** Name, Email, Position, Device Status, Actions
    -   **Device Status Display:**
        -   If device_token is NULL: Show badge “No Device Bound”
        -   If device_token exists: Show badge “Device Bound” \+ timestamp from device_bound_at
    -   **Actions Column:**
        -   “Reset Device” button (enabled only if device is bound)
        -   “Edit” button
        -   “Delete” button (with confirmation)

### **5.2 Device Reset Flow**

-   **Trigger:** Admin clicks “Reset Device” button on Counselor List
-   **Confirmation Modal:**
    -   **Title:** “Reset Device Lock for$$Counselor Name$$  
        ?”
    -   **Message:** “This will unbind the counselor’s current device. They will need to log in again to bind a new device.”
    -   **Buttons:** “Cancel” | “Reset Device”
-   **Action Logic:**
    -   Sets counselor_profiles.device_token to NULL
    -   Sets counselor_profiles.device_bound_at to NULL
    -   Logs action in system (optional audit trail)
-   **Success Feedback:**
    -   Toast notification: “Device lock reset successfully.$$Counselor Name$$  
        can now log in from a new device.”
    -   Table updates to show “No Device Bound” status
-   **Use Cases:**
    -   Counselor cleared browser cookies/cache
    -   Counselor switched to a different workstation
    -   Device was reformatted/replaced
    -   Troubleshooting login issues

### **5.3 User (Client) Management**

**Dashboard View:**

-   **Users Card:** Displays total count of registered clients (students)
-   **Card Content:**
    -   Large number showing total user count
    -   Arrow icon (→) on the right side
-   **Click Action:** Navigate to **Users Management Page**

**Users Management Page:**

-   **Header:** "Student Management"
-   **Stats Display:** "Total Students: {count}"
-   **Action Button:** "Add New Student" button
-   **Note:** No user list displayed (privacy consideration) - only the count

**Add Student Flow:**

1. **Trigger:** Click "Add New Student" button
2. **Modal Dialog:**
    - **Title:** "Add New Student"
    - **Form Fields:**
        - **Email Address** (required)
        - Validation: Must end with `@tupv.edu.ph`
    - **Buttons:** "Cancel" | "Send Invitation"
3. **Backend Logic:**

    ```php
    // Validate email domain
    if (!str_ends_with($email, '@tupv.edu.ph')) {
        return error('Only @tupv.edu.ph emails are allowed');
    }

    // Generate 8-character temporary password
    $tempPassword = Str::random(8);

    // Create inactive user (temp password is ONLY stored as hash)
    User::create([
        'name' => 'Pending Registration',
        'email' => $email,
        'password' => bcrypt($tempPassword),  // Hashed, not plain text
        'role' => 'client',
        'is_active' => false,
    ]);

    // Send email with temp password (only place it exists in plain text)
    Mail::to($email)->send(new StudentInvitation($email, $tempPassword));
    ```

    **Security:** The plain text temp password only exists in:

    1. Memory during account creation
    2. The email sent to the student

    It is NEVER stored in the database in plain text.

4. **Success Modal:**
    - **Title:** "Invitation Sent!"
    - **Message:** "An email with login credentials has been sent to {email}."
    - **Button:** "Done"
5. **User Count:** Automatically updates on page

**Email Template (StudentInvitation):**

```
Subject: Welcome to Paghupay - TUP-V Guidance & Counseling System

Dear Student,

You have been registered in the Paghupay Guidance & Counseling System.

Please complete your registration using the following credentials:

Email: {email}
Temporary Password: {temp_password}

Visit: {app_url}/register

IMPORTANT: You will be asked to create a new password and complete your profile.

Best regards,
TUP-V Guidance Office
```

**Validation Rules:**

-   Email must be unique in users table
-   Email must end with `@tupv.edu.ph`
-   Duplicate email shows error: "This email is already registered."

**Routes:**

```php
// Admin client management routes
Route::get('/admin/clients', [ClientController::class, 'index'])
    ->name('admin.clients.index');
Route::post('/admin/clients', [ClientController::class, 'store'])
    ->name('admin.clients.store');
```

## **6\. Security Implementation** ✅ IMPLEMENTED

### **6.1 Device Token Generation** ✅

**On Counselor’s First Login (after account creation):**

_// Generate unique device token_ $deviceToken \= hash('sha256', uniqid(mt_rand(), **true**) . $request-\>userAgent() . $request-\>ip());

_// Store in database_ $profile-\>update(\[

'device_token' \=\> $deviceToken,

'device_bound_at' \=\> now()

\]);

_// Set long-lived cookie (1 year)_ Cookie::queue('counselor_device_id', $deviceToken, 525600, '/', **null**, **true**, **true**);

_// Parameters: name, value, minutes, path, domain, secure, httpOnly_

### **6.2 Middleware: VerifyDevice (Trust on First Use)** ✅

**Location:** app/Http/Middleware/VerifyDevice.php

**Logic:**

**public** **function** handle($request, Closure $next)

{

$user \= Auth::user();

\*// Only apply to counselors\* \*\*if\*\* ($user\\-\\\>role \\\!== 'counselor') {    
    \*\*return\*\* $next($request);  
}

$profile \\= $user\\-\\\>counselorProfile;

\*// Case 1: First-time login (Trust on First Use)\* \*\*if\*\* (is\\\_null($profile\\-\\\>device\\\_token)) {  
 $deviceToken \\= hash('sha256', uniqid(mt\\\_rand(), \*\*true\*\*) . $request\\-\\\>userAgent() . $request\\-\\\>ip());

    $profile\\-\\\>update(\\\[
        'device\\\_token' \\=\\\> $deviceToken,
        'device\\\_bound\\\_at' \\=\\\> now()
    \\\]);

    Cookie::queue('counselor\\\_device\\\_id', $deviceToken, 525600, '/', \*\*null\*\*, \*\*true\*\*, \*\*true\*\*);

    \*\*return\*\* $next($request);

}

\*// Case 2: Verify existing device\* $storedToken \\= $profile\\-\\\>device\\\_token;    
$currentToken \\= $request\\-\\\>cookie('counselor\\\_device\\\_id');

\*\*if\*\* ($storedToken \\\!== $currentToken) {  
 Auth::logout();  
 \*\*return\*\* redirect('/counselor/login')-\\\>with('error',  
 'Unauthorized Device. This account is locked to a different device. Contact admin to reset.');  
}

\*\*return\*\* $next($request);

}

**Route Registration:**

_// routes/web.php_ Route::middleware(

$$'auth', 'role:counselor', 'verify.device'$$  
)

\-\>prefix('counselor')

\-\>group(**function** () {

_// All counselor routes here_ });

### **6.3 Middleware: RoleCheck** ✅

-   Ensures strict role segregation (Student cannot access Admin/Counselor routes).

### **6.4 Data Encryption Strategy (New in v1.5)**

To protect sensitive case logs from direct database access (e.g., DBA or unauthorized SQL dumps), specific columns will be encrypted using **Application-Level Encryption (AES-256-CBC)**.

Implementation (Laravel Eloquent):  
Laravel's built-in encryption features will automatically handle encryption on save and decryption on retrieval.  
**Model: CaseLog**

class CaseLog extends Model  
{  
 // These fields are stored as encrypted strings in the DB  
 protected $casts \= \[  
 'progress_report' \=\> 'encrypted',  
 'additional_notes' \=\> 'encrypted',  
 \];  
}

**Model: TreatmentGoal**

class TreatmentGoal extends Model  
{  
 protected $casts \= \[  
 'description' \=\> 'encrypted',  
 \];  
}

**Security Implications:**

1. **Database Inspection:** If an admin runs SELECT \* FROM case_logs;, the content will appear as eyJpdiI6... (unreadable ciphertext).
2. **App Access:** When a counselor views the logs via the app, Laravel uses the APP_KEY (stored in .env) to decrypt and display the text.
3. **Search Limitation:** Encrypted fields **cannot be searched** using standard SQL LIKE queries (e.g., you cannot search for "suicidal" in the notes easily). This is a necessary trade-off for security.

### **6.5 Security Notes**

-   **Cookie Settings:**
    -   httpOnly: true \- Prevents JavaScript access (XSS protection)
    -   secure: true \- Only sent over HTTPS (enable in production)
    -   sameSite: 'Lax' \- CSRF protection
-   **Token Storage:** SHA-256 hash ensures token cannot be reverse-engineered
-   **Reset Safety:** Only admins can unbind devices, preventing unauthorized access
-   **Audit Trail:** Consider logging all device resets with admin_id, counselor_id, timestamp

### **6.6 Admin Controller: Device Reset**

**Location:** app/Http/Controllers/Admin/CounselorController.php

**public** **function** resetDevice($counselorId)

{

$counselor \= User::with('counselorProfile')

\-\>where('role', 'counselor')

\-\>findOrFail($counselorId);

$profile \\= $counselor\\-\\\>counselorProfile;

\*// Reset device lock\* $profile\\-\\\>update(\\\[  
 'device\\\_token' \\=\\\> \*\*null\*\*,  
 'device\\\_bound\\\_at' \\=\\\> \*\*null\*\* \\\]);

\*// Optional: Log this action for audit trail\* \*// AuditLog::create(\\\[...\\\]);\*

\*\*return\*\* redirect()  
 \\-\\\>route('admin.counselors.index')  
 \\-\\\>with('success', "Device lock reset for {$counselor\\-\\\>name}. They can now log in from a new device.");

}

**Route:**

_// routes/web.php_ Route::middleware(

$$'auth', 'role:admin'$$  
)

\-\>prefix('admin')

\-\>group(**function** () {

Route::post('/counselors/{id}/reset-device',

$$CounselorController::\*\*class\*\*, 'resetDevice'$$  
)

\-\>name('admin.counselors.reset-device');

});

## **7\. UI/UX Specifications**

### **7.1 Admin Counselor List View**

**Bootstrap 5 Implementation:**

\<**div** class="card"\>

\<**div** class="card-header d-flex justify-content-between align-items-center"\>

\<**h5**\>Counselor Management\</**h5**\>

\<**button** class="btn btn-primary"\>Add New Counselor\</**button**\>

\</**div**\>

\<**div** class="card-body"\>

\<**table** class="table table-hover"\>

\<**thead**\>

\<**tr**\>

\<**th**\>Name\</**th**\>

\<**th**\>Email\</**th**\>

\<**th**\>Position\</**th**\>

\<**th**\>Device Status\</**th**\>

\<**th**\>Actions\</**th**\>

\</**tr**\>

\</**thead**\>

\<**tbody**\>

\<**tr**\>

\<**td**\>Dr. Maria Santos\</**td**\>

\<**td**\>maria.santos@tup.edu.ph\</**td**\>

\<**td**\>Head Psychologist\</**td**\>

\<**td**\>

\<**span** class="badge bg-success"\>

Device Bound

\<**small** class="d-block"\>Since: Dec 10, 2025\</**small**\>

\</**span**\>

\</**td**\>

\<**td**\>

\<**button** class="btn btn-sm btn-warning"

data-bs-toggle="modal"

data-bs-target="\#resetModal"

\>

Reset Device

\</**button**\>

\<**button** class="btn btn-sm btn-secondary"\>Edit\</**button**\>

\</**td**\>

\</**tr**\>

\</**tbody**\>

\</**table**\>

\</**div**\>

\</**div**\>

### **7.2 Reset Device Modal**

\<**div** class="modal fade" id="resetModal" tabindex="-1"\>

\<**div** class="modal-dialog"\>

\<**div** class="modal-content"\>

\<**div** class="modal-header"\>

\<**h5** class="modal-title"\>Reset Device Lock\</**h5**\>

\<**button** type="button"

class="btn-close"

data-bs-dismiss="modal"

\>\</**button**\>

\</**div**\>

\<**div** class="modal-body"\>

\<**p**\>

Are you sure you want to reset the device lock for

\<**strong**\>

$$Counselor Name$$  
\</**strong**\>?

\</**p**\>

\<**p** class="text-muted"\>

They will need to log in again to bind a new device.

\</**p**\>

\</**div**\>

\<**div** class="modal-footer"\>

\<**button** type="button" class="btn btn-secondary" data-bs-dismiss="modal"\>

Cancel

\</**button**\>

\<**form** method="POST" action="/admin/counselors/{id}/reset-device"\>

@csrf

\<**button** type="submit" class="btn btn-warning"\>Reset Device\</**button**\>

\</**form**\>

\</**div**\>

\</**div**\>

\</**div**\>

\</**div**\>

## **8\. Testing Checklist**

### **8.1 Authentication Testing** ✅ READY FOR TESTING

-   ☐ Student can access /login and see blue-themed login form
-   ☐ Counselor can access /counselor/login and see green-themed login form
-   ☐ Admin can access /admin/login and see red-themed login form
-   ☐ Student can register at /register with Data Privacy agreement
-   ☐ Wrong role login attempt shows appropriate error message
-   ☐ Successful login redirects to correct dashboard per role
-   ☐ New student registration redirects to /onboarding
-   ☐ Logout works and redirects to /login

### **8.2 Device Lock Testing** ✅ READY FOR TESTING

-   ☐ Counselor logs in for first time → Device token created and cookie set
-   ☐ Counselor logs out and back in → Access granted (same device)
-   ☐ Counselor clears cookies → Access denied on next login
-   ☐ Admin resets device → Counselor can log in from new device
-   ☐ Counselor tries to log in from different computer → Access denied
-   ☐ Cookie expires after 1 year → New device binding required

### **8.3 Admin Flow Testing**

-   ☐ Device status badge shows correctly (bound vs unbound)
-   ☐ Reset button is disabled when device is not bound
-   ☐ Reset confirmation modal displays counselor name
-   ☐ Reset action clears device_token and device_bound_at
-   ☐ Success message appears after reset
-   ☐ Table updates to show "No Device Bound" status

### **8.4 Security Testing** ✅ READY FOR TESTING

-   ☐ Cookie is httpOnly and cannot be read via JavaScript
-   ☐ Middleware blocks access without valid token
-   ☐ Role-based access control works (no cross-role access)
-   ☐ Logout clears authentication but preserves device cookie
-   ☐ Token tampering results in access denial
-   ☐ **\[New\]** Direct Database Access Check: Run SQL SELECT on case_logs. Ensure progress_report is unreadable ciphertext.
