# **Spec-Driven Development: TUP-V Guidance & Counseling System**

Version: 1.6 (Device Lock \+ Data Encryption \+ SendGrid)

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

## **2\. Authentication & Login Pages**

**Distinct Login Pages Required:**

1. **Student Login:** /login (Default)
2. **Counselor Login:** /counselor/login
3. **Admin Login:** /admin/login

## **3\. Client (Student) Portal Flows**

### **3.1 Registration & Onboarding**

-   **Flow:** Student Login Page \-\> Link to “Register” \-\> **Registration Form** \-\> **Terms of Agreement** \-\> **Student Welcome Page**.
-   **Registration Form:** User fills in all profile fields (nickname, course, address, etc.) which were initially null.
-   **Terms:** Must check “Agree to Data Privacy Act” to proceed.
-   **Outcome:** users.is_active set to true.

### **3.2 Booking Flow (Complete Cycle)**

-   **Start:** **Student Welcome Page**.
-   **Step 1:** Click “Start” \-\> Redirect to **Choose Counselor Page**.
-   **Step 2:** Select Counselor \-\> Redirect to **Date & Time Selection**.
-   **Step 3:** Select Date/Time \-\> Redirect to **Reason Input**.
-   **Step 4:** Submit \-\> Redirect to **“Receive Email” Page**.
-   **Step 5:** Click “Back” \-\> Returns to **Student Welcome Page**.

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

-   **Flow:** Dashboard \-\> **Clients/Users Card**.
-   **Add User:**
    -   **Form:** **Email Address** (Only).
    -   **System:** Generates temp_password, creates Inactive Client, sends Email.

## **6\. Security Implementation**

### **6.1 Device Token Generation**

**On Counselor’s First Login (after account creation):**

_// Generate unique device token_ $deviceToken \= hash('sha256', uniqid(mt_rand(), **true**) . $request-\>userAgent() . $request-\>ip());

_// Store in database_ $profile-\>update(\[

'device_token' \=\> $deviceToken,

'device_bound_at' \=\> now()

\]);

_// Set long-lived cookie (1 year)_ Cookie::queue('counselor_device_id', $deviceToken, 525600, '/', **null**, **true**, **true**);

_// Parameters: name, value, minutes, path, domain, secure, httpOnly_

### **6.2 Middleware: VerifyDevice (Trust on First Use)**

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

### **6.3 Middleware: RoleCheck**

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

### **8.1 Device Lock Testing**

-   ☐ Counselor logs in for first time → Device token created and cookie set
-   ☐ Counselor logs out and back in → Access granted (same device)
-   ☐ Counselor clears cookies → Access denied on next login
-   ☐ Admin resets device → Counselor can log in from new device
-   ☐ Counselor tries to log in from different computer → Access denied
-   ☐ Cookie expires after 1 year → New device binding required

### **8.2 Admin Flow Testing**

-   ☐ Device status badge shows correctly (bound vs unbound)
-   ☐ Reset button is disabled when device is not bound
-   ☐ Reset confirmation modal displays counselor name
-   ☐ Reset action clears device_token and device_bound_at
-   ☐ Success message appears after reset
-   ☐ Table updates to show “No Device Bound” status

### **8.3 Security Testing**

-   ☐ Cookie is httpOnly and cannot be read via JavaScript
-   ☐ Middleware blocks access without valid token
-   ☐ Role-based access control works (no cross-role access)
-   ☐ Logout clears authentication but preserves device cookie
-   ☐ Token tampering results in access denial
-   ☐ **\[New\]** Direct Database Access Check: Run SQL SELECT on case_logs. Ensure progress_report is unreadable ciphertext.
