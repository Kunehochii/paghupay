# Paghupay Setup Guide

> **For Students**: This guide walks you through setting up and running the Paghupay (TUP-V Guidance & Counseling System) on your local machine from scratch.

---

## 📋 Prerequisites Overview

Before running Paghupay, you need to install:

| Software | Version | Purpose                |
| -------- | ------- | ---------------------- |
| PHP      | 8.2+    | Backend runtime        |
| Composer | Latest  | PHP dependency manager |
| Node.js  | 18+ LTS | Frontend build tools   |
| MySQL    | 8.0+    | Database               |
| SendGrid | -       | Email service (SMTP)   |

---

## 1️⃣ Install PHP (Windows)

### Option A: Using XAMPP (Recommended for Beginners)

1. Download XAMPP from: https://www.apachefriends.org/download.html
2. Choose the **PHP 8.2+** version
3. Run the installer and install to `C:\xampp`
4. After installation, add PHP to your system PATH:

    - Press `Win + R`, type `sysdm.cpl`, press Enter
    - Go to **Advanced** tab → **Environment Variables**
    - Under **System Variables**, find `Path` and click **Edit**
    - Click **New** and add: `C:\xampp\php`
    - Click **OK** on all dialogs

5. Verify installation by opening a **new** terminal:
    ```powershell
    php -v
    ```
    You should see PHP version 8.2 or higher.

### Option B: Standalone PHP Installation

1. Download PHP from: https://windows.php.net/download/
2. Choose **VS16 x64 Thread Safe** zip
3. Extract to `C:\php`
4. Add `C:\php` to your system PATH (same steps as above)

---

## ⚠️ IMPORTANT: Enable PHP Extensions

This step is **critical** for Composer to work efficiently.

### Enable the `zip` Extension

The `zip` extension dramatically speeds up Composer package downloads. Without it, Composer falls back to slow file-by-file extraction.

1. Navigate to your PHP installation folder:

    - XAMPP: `C:\xampp\php\`
    - Standalone: `C:\php\`

2. Find the file `php.ini` (if you only see `php.ini-development`, copy it and rename to `php.ini`)

3. Open `php.ini` in a text editor (Notepad, VS Code, etc.)

4. Find and **uncomment** (remove the `;`) these lines:

    ```ini
    ;extension=zip
    ```

    Change to:

    ```ini
    extension=zip
    ```

5. Also enable these required extensions for Laravel:

    ```ini
    extension=curl
    extension=fileinfo
    extension=mbstring
    extension=openssl
    extension=pdo_mysql
    extension=gd
    ```

6. **Save** the file and **restart** any open terminals.

7. Verify extensions are enabled:

    ```powershell
    php -m | findstr zip
    php -m | findstr pdo_mysql
    ```

    Both commands should return the extension name.

---

## 2️⃣ Install Composer

Composer is the PHP dependency manager (like npm for JavaScript).

1. Download the Composer installer from: https://getcomposer.org/download/
2. Run `Composer-Setup.exe`
3. The installer will automatically detect your PHP installation
4. Complete the installation with default settings

5. Verify installation:
    ```powershell
    composer -V
    ```

---

## 3️⃣ Install Node.js

Node.js is required for building frontend assets (CSS/JavaScript).

1. Download Node.js **LTS version** from: https://nodejs.org/
2. Run the installer with default settings
3. **Important**: Check the box for "Automatically install necessary tools" if prompted

4. Verify installation (open a **new** terminal):
    ```powershell
    node -v
    npm -v
    ```

---

## 4️⃣ Install MySQL

### Option A: Using XAMPP (If you installed XAMPP for PHP)

MySQL is already included! Just:

1. Open XAMPP Control Panel
2. Click **Start** next to **MySQL**
3. MySQL will run on `localhost:3306`

### Option B: Standalone MySQL Installation

1. Download MySQL Installer from: https://dev.mysql.com/downloads/installer/
2. Choose **MySQL Installer (Web)** - smaller download
3. During setup, select:
    - **MySQL Server 8.0**
    - **MySQL Workbench** (optional, for GUI management)
4. Set a root password (remember this!)
5. Complete the installation

### Create the Database

1. Open a terminal and connect to MySQL:

    ```powershell
    # If using XAMPP
    C:\xampp\mysql\bin\mysql -u root

    # If standalone MySQL
    mysql -u root -p
    ```

2. Create the database:
    ```sql
    CREATE DATABASE paghupay;
    EXIT;
    ```

---

## 5️⃣ Set Up SendGrid (Email Service)

Paghupay uses SendGrid to send email notifications (appointment confirmations, student invitations, etc.).

### Step 1: Create a SendGrid Account

1. Go to https://signup.sendgrid.com/
2. Sign up for a **free account** (100 emails/day free)
3. Complete email verification

### Step 2: Create a Single Sender Identity

**Important**: SendGrid requires sender verification before you can send emails.

1. After logging in, go to **Settings** → **Sender Authentication**
2. Click **Verify a Single Sender**
3. Fill in the form:
    - **From Name**: `Paghupay` or `TUP-V Guidance Office`
    - **From Email Address**: Your email (e.g., `guidance@tupv.edu.ph` or your personal email for testing)
    - **Reply To**: Same as from email
    - **Company Address**: Your institution's address
    - **City, Country**: Fill accordingly
4. Click **Create**
5. Check your email inbox and click the **verification link** sent by SendGrid

### Step 3: Generate an API Key

1. In SendGrid dashboard, go to **Settings** → **API Keys**
2. Click **Create API Key**
3. Name it: `Paghupay Local`
4. Select **Full Access** (or Restricted Access with Mail Send permission)
5. Click **Create & View**
6. **COPY THE API KEY NOW** - it will only be shown once!
    - It looks like: `SG.xxxxxxxxxxxxxxxxxxxxxx.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`
7. Save this key somewhere safe temporarily

---

## 6️⃣ Project Setup

### Step 1: Clone or Extract the Project

If you received the project as a ZIP file:

```powershell
# Extract to your desired location, e.g.:
cd D:\projects
# Extract paghupay.zip here
```

If cloning from Git:

```powershell
git clone <repository-url> paghupay
cd paghupay
```

### Step 2: Install PHP Dependencies

Navigate to the project folder and run:

```powershell
cd D:\tupv\paghupay
composer install
```

This will download all Laravel dependencies. With the `zip` extension enabled, this should take 2-5 minutes instead of 10-15 minutes.

### Step 3: Install Node.js Dependencies

```powershell
npm install
```

### Step 4: Create Environment File

Copy the example environment file:

```powershell
copy .env.example .env
```

### Step 5: Configure the `.env` File

Open `.env` in VS Code or any text editor and update these values:

```env
# Application Settings
APP_NAME=Paghupay
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration (MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=paghupay
DB_USERNAME=root
DB_PASSWORD=

# If you set a MySQL root password, add it above
# DB_PASSWORD=your_mysql_password

# SendGrid SMTP Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.your-actual-sendgrid-api-key-here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-verified-sender@email.com"
MAIL_FROM_NAME="Paghupay"
```

**Important Notes:**

-   `MAIL_USERNAME` must be exactly `apikey` (this is SendGrid's requirement)
-   `MAIL_PASSWORD` is your SendGrid API key (starts with `SG.`)
-   `MAIL_FROM_ADDRESS` must match the email you verified in SendGrid's Single Sender

### Step 6: Generate Application Key

```powershell
php artisan key:generate
```

This generates a unique encryption key for your application.

### Step 7: Run Database Migrations

This creates all the database tables:

```powershell
php artisan migrate
```

### Step 8: Seed the Database (Optional)

To add default time slots:

```powershell
php artisan db:seed
```

### Step 9: Create Storage Link

This enables file uploads to work correctly:

```powershell
php artisan storage:link
```

---

## 7️⃣ Running the Application

You need to run **two terminals** simultaneously:

### Terminal 1: PHP Development Server

```powershell
cd D:\tupv\paghupay
php artisan serve
```

This starts the backend server at `http://localhost:8000`

### Terminal 2: Vite Development Server (Frontend Assets)

Open a **new** terminal:

```powershell
cd D:\tupv\paghupay
npm run dev
```

This compiles CSS/JavaScript and enables hot-reloading.

### Access the Application

Open your browser and go to:

-   **Student Login**: http://localhost:8000/login
-   **Counselor Login**: http://localhost:8000/counselor/login
-   **Admin Login**: http://localhost:8000/admin/login

---

## 8️⃣ Creating Test Users

To create test users, use Laravel Tinker:

```powershell
php artisan tinker
```

Then run these commands:

### Create an Admin User

```php
use App\Models\User;

User::create([
    'name' => 'Admin User',
    'email' => 'admin@tupv.edu.ph',
    'password' => bcrypt('password123'),
    'role' => 'admin',
    'is_active' => true,
]);
```

### Create a Student User

```php
User::create([
    'name' => 'Student User',
    'email' => 'student@tupv.edu.ph',
    'password' => bcrypt('password123'),
    'role' => 'client',
    'is_active' => true,
]);
```

### Create a Counselor User

```php
use App\Models\CounselorProfile;

$counselor = User::create([
    'name' => 'Dr. Maria Santos',
    'email' => 'counselor@tupv.edu.ph',
    'password' => bcrypt('password123'),
    'role' => 'counselor',
    'is_active' => true,
]);

CounselorProfile::create([
    'user_id' => $counselor->id,
    'position' => 'Head Psychologist',
]);
```

Type `exit` to leave Tinker.

---

## 🔧 Troubleshooting

### "Class 'ZipArchive' not found" or Composer is slow

**Solution**: Enable the `zip` extension in `php.ini` (see Section 1).

### "SQLSTATE[HY000] [2002] Connection refused"

**Solution**:

1. Make sure MySQL is running
2. Check your `.env` database credentials
3. Verify the database `paghupay` exists

### "Failed to authenticate on SMTP server" (Email)

**Solution**:

1. Verify `MAIL_PASSWORD` is your SendGrid API key (starts with `SG.`)
2. Verify `MAIL_USERNAME` is exactly `apikey`
3. Verify your sender email is verified in SendGrid

### "Vite manifest not found"

**Solution**: Make sure `npm run dev` is running in a separate terminal.

### "Permission denied" errors

**Solution** (Windows): Run your terminal as Administrator.

---

## 📁 Quick Reference Commands

| Command                     | Purpose                          |
| --------------------------- | -------------------------------- |
| `php artisan serve`         | Start PHP development server     |
| `npm run dev`               | Start Vite dev server (frontend) |
| `npm run build`             | Build frontend for production    |
| `php artisan migrate`       | Run database migrations          |
| `php artisan migrate:fresh` | Reset and re-run all migrations  |
| `php artisan db:seed`       | Seed the database with defaults  |
| `php artisan tinker`        | Interactive PHP shell            |
| `php artisan cache:clear`   | Clear application cache          |
| `php artisan config:clear`  | Clear configuration cache        |
| `composer install`          | Install PHP dependencies         |
| `npm install`               | Install Node.js dependencies     |

---

## 🛑 Stopping the Servers

-   Press `Ctrl + C` in each terminal to stop the servers
-   To stop MySQL (XAMPP): Open XAMPP Control Panel and click **Stop** next to MySQL

---

## 📞 Need Help?

If you encounter issues not covered in this guide:

1. Check the Laravel documentation: https://laravel.com/docs
2. Search the error message on Google or Stack Overflow
3. Contact your instructor or project supervisor

---

_Last Updated: January 2026_
