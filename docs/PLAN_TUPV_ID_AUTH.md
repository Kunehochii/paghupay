# Plan: TUPV ID Authentication Migration

> **Version:** 1.0  
> **Date:** January 11, 2026  
> **Status:** Draft  
> **Database Migration:** PostgreSQL → MySQL

---

## 📋 Overview

This plan outlines the changes required to migrate the authentication system from **email-based login** to **TUPV ID-based login** for both **Students (Clients)** and **Admins**.

### Key Changes Summary

| Role      | Current Login Field | New Login Field | Format                     |
| :-------- | :------------------ | :-------------- | :------------------------- |
| Student   | Email               | TUPV ID         | `TUPV-XX-XXXX`             |
| Admin     | Email               | Admin ID        | Custom (e.g., `ADMIN-001`) |
| Counselor | Email (unchanged)   | Email           | `@tupv.edu.ph`             |

### TUPV ID Format

-   **Pattern:** `TUPV-XX-XXXX`
-   **XX:** Year of enrollment (2-digit, e.g., `24` for 2024)
-   **XXXX:** Student number (4-digit, e.g., `0001`)
-   **Example:** `TUPV-24-0001`

### Admin ID Format

-   **Pattern:** `ADMIN-XXX` or custom
-   **Example:** `ADMIN-001`, `SYS-ADMIN`

---

## 🗂️ Affected Files

### 1. Database Migrations

| File                                                           | Change Required                                                                   |
| :------------------------------------------------------------- | :-------------------------------------------------------------------------------- |
| `database/migrations/0001_01_01_000000_create_users_table.php` | Add `tupv_id` column (unique, nullable), add `admin_id` column (unique, nullable) |
| New migration file                                             | Add columns to existing users table (for MySQL migration)                         |

### 2. Models

| File                  | Change Required                                                                  |
| :-------------------- | :------------------------------------------------------------------------------- |
| `app/Models/User.php` | Add `tupv_id` and `admin_id` to `$fillable`, add accessor/mutator for validation |

### 3. Auth Controller

| File                                           | Change Required                                                                                     |
| :--------------------------------------------- | :-------------------------------------------------------------------------------------------------- |
| `app/Http/Controllers/Auth/AuthController.php` | Change `login()` to use `tupv_id`, change `adminLogin()` to use `admin_id`, update validation rules |

### 4. Views - Login Pages

| File                                         | Change Required                                                         |
| :------------------------------------------- | :---------------------------------------------------------------------- |
| `resources/views/auth/login.blade.php`       | Change email input to TUPV ID input, update labels, update placeholder  |
| `resources/views/auth/admin-login.blade.php` | Change email input to Admin ID input, update labels, update placeholder |
| `resources/views/auth/register.blade.php`    | If applicable, add TUPV ID field for profile completion                 |

### 5. Admin Client Management

| File                                                  | Change Required                                                                                |
| :---------------------------------------------------- | :--------------------------------------------------------------------------------------------- |
| `app/Http/Controllers/Admin/ClientController.php`     | Change student creation to require TUPV ID instead of email, email becomes optional info field |
| `resources/views/admin/clients/index.blade.php`       | Update modal form fields (TUPV ID primary, email secondary)                                    |
| `app/Mail/StudentInvitation.php`                      | Update email template to include TUPV ID                                                       |
| `resources/views/emails/student-invitation.blade.php` | Update template content                                                                        |

### 6. Case Logs

| File                                                   | Change Required                                                            |
| :----------------------------------------------------- | :------------------------------------------------------------------------- |
| `app/Http/Controllers/Counselor/CaseLogController.php` | Change student lookup from dropdown to TUPV ID text input with validation  |
| `resources/views/counselor/case-logs/create.blade.php` | Replace student dropdown with TUPV ID text input field                     |
| `resources/views/counselor/case-logs/index.blade.php`  | Update "TUPV ID" column to show student's TUPV ID instead of `case_log_id` |
| `resources/views/counselor/case-logs/show.blade.php`   | Display student's TUPV ID                                                  |
| `resources/views/counselor/case-logs/edit.blade.php`   | Display student's TUPV ID (read-only)                                      |
| `resources/views/counselor/case-logs/pdf.blade.php`    | Include student's TUPV ID                                                  |

### 7. Database Seeders

| File                                  | Change Required                                      |
| :------------------------------------ | :--------------------------------------------------- |
| `database/seeders/DatabaseSeeder.php` | Add `tupv_id` for students, add `admin_id` for admin |

### 8. Documentation

| File                    | Change Required                                |
| :---------------------- | :--------------------------------------------- |
| `docs/AGENT_CONTEXT.md` | Update authentication section, database schema |
| `docs/PAGHUPAY_SPEC.md` | Update specification for new auth flow         |

---

## 📊 Database Schema Changes

### Users Table - New Columns

```sql
ALTER TABLE users ADD COLUMN tupv_id VARCHAR(15) NULL UNIQUE;
-- Format: TUPV-XX-XXXX (15 chars max)
-- Only for role = 'client'

ALTER TABLE users ADD COLUMN admin_id VARCHAR(20) NULL UNIQUE;
-- Format: Flexible (e.g., ADMIN-001)
-- Only for role = 'admin'
```

### Updated Users Table Schema

| Column   | Type        | Nullable | Notes                                          |
| :------- | :---------- | :------- | :--------------------------------------------- |
| id       | BIGINT      | NO       | Primary Key (auto-increment)                   |
| tupv_id  | VARCHAR(15) | YES      | **NEW** - Unique, for students only            |
| admin_id | VARCHAR(20) | YES      | **NEW** - Unique, for admins only              |
| name     | VARCHAR     | NO       | Full Name                                      |
| email    | VARCHAR     | YES\*    | Changed from NOT NULL to nullable for students |
| password | VARCHAR     | NO       | Hashed (Bcrypt)                                |
| role     | ENUM        | NO       | admin, client, counselor                       |
| ...      | ...         | ...      | (other profile fields unchanged)               |

> **Note:** Email becomes nullable for students but remains required for counselors (device binding + notifications).

### Index Changes

```sql
-- New indexes for login performance
CREATE INDEX idx_users_tupv_id ON users(tupv_id);
CREATE INDEX idx_users_admin_id ON users(admin_id);
```

---

## 🔐 Authentication Flow Changes

### Student Login (Before)

```
1. Enter email + password
2. Auth::attempt(['email' => $email, 'password' => $password])
3. Validate role === 'client'
4. Redirect to dashboard
```

### Student Login (After)

```
1. Enter TUPV ID + password
2. Auth::attempt(['tupv_id' => $tupvId, 'password' => $password])
3. Validate role === 'client'
4. Redirect to dashboard
```

### Admin Login (Before)

```
1. Enter email + password
2. Auth::attempt(['email' => $email, 'password' => $password])
3. Validate role === 'admin'
4. Redirect to dashboard
```

### Admin Login (After)

```
1. Enter Admin ID + password
2. Auth::attempt(['admin_id' => $adminId, 'password' => $password])
3. Validate role === 'admin'
4. Redirect to dashboard
```

### Counselor Login (Unchanged)

```
1. Enter email + password
2. Auth::attempt(['email' => $email, 'password' => $password])
3. Validate role === 'counselor'
4. Device verification (TOFU)
5. Redirect to dashboard
```

---

## 📝 Case Log Changes

### Current Implementation

The case log index displays `case_log_id` (format: `TUPV-{UUID}`) in the "TUPV ID" column. This is confusing because:

-   `case_log_id` is a system-generated UUID for the case log itself
-   The column header says "TUPV ID" but shows the case log identifier

### Proposed Changes

1. **Case Log Index Table:**

    - "TUPV ID" column should display `$caseLog->client->tupv_id` (student's ID)
    - Keep case log reference number as separate "Log #" column (already exists)

2. **Case Log Creation:**

    - Replace student dropdown (`<select>`) with TUPV ID text input (`<input type="text">`)
    - Add AJAX validation to verify TUPV ID exists in the system
    - On valid TUPV ID entry, display student name for confirmation

3. **Student Lookup Logic:**

    ```php
    // Before: Dropdown with client_id
    $validated = $request->validate([
        'client_id' => 'required|exists:users,id',
    ]);

    // After: TUPV ID text input
    $validated = $request->validate([
        'tupv_id' => ['required', 'string', 'regex:/^TUPV-\d{2}-\d{4}$/'],
    ]);

    $client = User::where('tupv_id', $validated['tupv_id'])
        ->where('role', 'client')
        ->firstOrFail();
    ```

---

## 🛠️ Implementation Steps

### Phase 1: Database Migration (MySQL)

1. [ ] Create new migration for `tupv_id` and `admin_id` columns
2. [ ] Update existing migration file for fresh installs
3. [ ] Add database indexes for new columns
4. [ ] Update User model with new fillable fields

### Phase 2: Authentication Changes

1. [ ] Update AuthController for student login (tupv_id)
2. [ ] Update AuthController for admin login (admin_id)
3. [ ] Update student login view
4. [ ] Update admin login view
5. [ ] Update validation error messages

### Phase 3: Admin Client Management

1. [ ] Update ClientController to create students with TUPV ID
2. [ ] Update add student modal (TUPV ID as primary field)
3. [ ] Update email invitation to include TUPV ID
4. [ ] Make email field optional but recommended

### Phase 4: Case Log Updates

1. [ ] Update CaseLogController::create() - TUPV ID input instead of dropdown
2. [ ] Add AJAX endpoint for TUPV ID validation
3. [ ] Update create.blade.php view
4. [ ] Update index.blade.php to show student's TUPV ID
5. [ ] Update show.blade.php, edit.blade.php, pdf.blade.php

### Phase 5: Seeder & Testing

1. [ ] Update DatabaseSeeder with sample TUPV IDs and Admin IDs
2. [ ] Test all login flows
3. [ ] Test case log creation with TUPV ID lookup
4. [ ] Test student invitation flow

### Phase 6: Documentation

1. [ ] Update AGENT_CONTEXT.md
2. [ ] Update PAGHUPAY_SPEC.md

---

## 📌 Validation Rules

### TUPV ID Validation

```php
'tupv_id' => [
    'required',
    'string',
    'regex:/^TUPV-\d{2}-\d{4}$/',
    'unique:users,tupv_id',
],
```

**Error Messages:**

-   Invalid format: "TUPV ID must be in format TUPV-XX-XXXX (e.g., TUPV-24-0001)"
-   Already exists: "This TUPV ID is already registered."

### Admin ID Validation

```php
'admin_id' => [
    'required',
    'string',
    'min:3',
    'max:20',
    'unique:users,admin_id',
],
```

---

## 🎨 UI Changes

### Student Login Page

**Before:**

```html
<input type="email" name="email" placeholder="Email Address" />
<label>Email Address</label>
```

**After:**

```html
<input
    type="text"
    name="tupv_id"
    placeholder="TUPV-XX-XXXX"
    pattern="TUPV-\d{2}-\d{4}"
/>
<label>TUPV ID</label>
<small class="form-text text-muted">Format: TUPV-XX-XXXX</small>
```

### Admin Login Page

**Before:**

```html
<input type="email" name="email" placeholder="Admin Email" />
<label>Email Address</label>
```

**After:**

```html
<input type="text" name="admin_id" placeholder="Admin ID" />
<label>Admin ID</label>
```

### Case Log Create Page - Student Selection

**Before:**

```html
<select name="client_id">
    <option value="">Select Student</option>
    @foreach($clients as $client)
    <option value="{{ $client->id }}">{{ $client->name }}</option>
    @endforeach
</select>
```

**After:**

```html
<input type="text" name="tupv_id" placeholder="TUPV-XX-XXXX" id="tupvIdInput" />
<div id="studentPreview" class="mt-2 d-none">
    <span class="badge bg-success">
        <i class="bi bi-check-circle"></i>
        Student Found: <span id="studentName"></span>
    </span>
</div>
<div id="studentError" class="text-danger mt-2 d-none">Student not found</div>
```

---

## 🧪 Test Seeder Data

```php
// Database Seeder - Updated

// Admin with Admin ID
User::create([
    'name' => 'System Administrator',
    'admin_id' => 'ADMIN-001',
    'email' => 'admin@tupv.edu.ph', // Optional, for reference
    'password' => Hash::make('admin123'),
    'role' => 'admin',
    'is_active' => true,
]);

// Student with TUPV ID
User::create([
    'name' => 'Juan Dela Cruz',
    'tupv_id' => 'TUPV-24-0001',
    'email' => 'juan.delacruz@tupv.edu.ph', // Optional
    'password' => Hash::make('student123'),
    'role' => 'client',
    'is_active' => false,
]);

// Another Student
User::create([
    'name' => 'Maria Santos',
    'tupv_id' => 'TUPV-23-0042',
    'email' => null, // Email is now optional
    'password' => Hash::make('student123'),
    'role' => 'client',
    'is_active' => true,
]);
```

---

## ⚠️ Breaking Changes & Migration Notes

### For Existing Users

If migrating from an existing database:

1. **Students without TUPV ID:** Will need to be assigned TUPV IDs manually or through a bulk update script
2. **Admin without Admin ID:** Will need to be assigned Admin ID

### Email Field Changes

-   **Students:** Email becomes optional (informational only)
-   **Counselors:** Email remains required (for device binding and notifications)
-   **Admins:** Email becomes optional

### case_log_id vs tupv_id Clarification

| Field         | Table     | Purpose                                                           |
| :------------ | :-------- | :---------------------------------------------------------------- |
| `case_log_id` | case_logs | Unique identifier for the case log record (format: `TUPV-{UUID}`) |
| `tupv_id`     | users     | Student's TUPV enrollment ID (format: `TUPV-XX-XXXX`)             |

> **Important:** These are different identifiers. The `case_log_id` is an internal system ID, while `tupv_id` is the student's actual enrollment ID.

---

## 📋 Checklist

### Pre-Implementation

-   [ ] Review and approve this plan
-   [ ] Backup existing database
-   [ ] Set up MySQL database environment

### Implementation

-   [ ] Phase 1: Database Migration
-   [ ] Phase 2: Authentication Changes
-   [ ] Phase 3: Admin Client Management
-   [ ] Phase 4: Case Log Updates
-   [ ] Phase 5: Seeder & Testing
-   [ ] Phase 6: Documentation

### Post-Implementation

-   [ ] Test all login flows (Student, Admin, Counselor)
-   [ ] Test case log creation with TUPV ID lookup
-   [ ] Test student invitation flow
-   [ ] Update any API documentation
-   [ ] Deploy to staging/testing environment

---

## 🔄 Rollback Plan

If issues arise:

1. Revert migration (drop new columns)
2. Restore original AuthController logic
3. Restore original view files
4. Re-run original seeders

---

**Document Prepared By:** AI Assistant  
**Review Required:** Project Lead  
**Approved By:** **********\_\_\_**********  
**Approval Date:** **********\_\_\_**********
