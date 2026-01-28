# Paghupay Setup Guide

> **For Students**: This guide walks you through setting up and running the Paghupay (TUP-V Guidance & Counseling System) on your local machine from scratch.

---

## 📋 Prerequisites Overview

Before running Paghupay, you need to install:

| Software | Version | Purpose                |
| -------- | ------- | ---------------------- |
| VS Code  | Latest  | Code editor & terminal |
| Git      | Latest  | Version control        |
| PHP      | 8.2+    | Backend runtime        |
| Composer | Latest  | PHP dependency manager |
| Node.js  | 18+ LTS | Frontend build tools   |
| MySQL    | 8.0+    | Database               |
| SendGrid | -       | Email service (SMTP)   |

---

## 1️⃣ Install Visual Studio Code

VS Code is the recommended code editor for this project.

### Download & Install

1. Go to: https://code.visualstudio.com/
2. Click **Download for Windows** (or Mac)
3. Run the installer
4. **Important**: During installation, check these options:
    - ✅ Add "Open with Code" action to Windows Explorer file context menu
    - ✅ Add "Open with Code" action to Windows Explorer directory context menu
    - ✅ Add to PATH

### Required Extensions

After installing VS Code, install these extensions for the best development experience:

1. Open VS Code
2. Click the **Extensions** icon in the sidebar (or press `Ctrl+Shift+X`)
3. Search and install each of these:

| Extension                   | Publisher          | Purpose                        |
| --------------------------- | ------------------ | ------------------------------ |
| **PHP Intelephense**        | Ben Mewburn        | PHP code intelligence          |
| **Laravel Blade Snippets**  | Winnie Lin         | Blade template support         |
| **Laravel Blade formatter** | Shuhei Hayashibara | Format Blade files             |
| **DotENV**                  | mikestead          | .env file syntax highlight     |
| **GitLens**                 | GitKraken          | Enhanced Git integration       |
| **MySQL**                   | Weijan Chen        | Database management (optional) |

**Quick Install via Command Palette:**

Press `Ctrl+Shift+P` (Windows) or `Cmd+Shift+P` (Mac), type "Install Extensions", then search for each extension name.

---

## 2️⃣ Install Git

Git is required for version control and cloning the project from GitHub.

### Windows Installation

1. Download Git from: https://git-scm.com/download/windows
2. Run the installer
3. **Important settings during installation:**
    - Choose **"Use Visual Studio Code as Git's default editor"**
    - Choose **"Git from the command line and also from 3rd-party software"**
    - Keep other defaults
4. Complete the installation

### Mac Installation

**Option A: Using Homebrew (Recommended)**

```bash
# Install Homebrew first if you don't have it
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Then install Git
brew install git
```

**Option B: Using Xcode Command Line Tools**

```bash
xcode-select --install
```

### Verify Git Installation

Open a **new** terminal and run:

```bash
git --version
```

You should see something like `git version 2.x.x`.

### Configure Git (First Time Only)

Set your name and email (use your real info):

```bash
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"
```

---

## 3️⃣ Install PHP

### Windows

#### Option A: Using XAMPP (Recommended for Beginners)

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

#### Option B: Standalone PHP Installation

1. Download PHP from: https://windows.php.net/download/
2. Choose **VS16 x64 Thread Safe** zip
3. Extract to `C:\php`
4. Add `C:\php` to your system PATH (same steps as above)

### Mac

**Using Homebrew (Recommended):**

```bash
brew install php
```

Verify installation:

```bash
php -v
```

---

## ⚠️ IMPORTANT: Enable PHP Extensions (Windows Only)

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

## 4️⃣ Install Composer

Composer is the PHP dependency manager (like npm for JavaScript).

### Windows

1. Download the Composer installer from: https://getcomposer.org/download/
2. Run `Composer-Setup.exe`
3. The installer will automatically detect your PHP installation
4. Complete the installation with default settings

### Mac

```bash
brew install composer
```

### Verify Installation

```bash
composer -V
```

---

## 5️⃣ Install Node.js

Node.js is required for building frontend assets (CSS/JavaScript).

### Windows & Mac

1. Download Node.js **LTS version** from: https://nodejs.org/
2. Run the installer with default settings
3. **Important**: Check the box for "Automatically install necessary tools" if prompted

### Mac (Alternative via Homebrew)

```bash
brew install node
```

### Verify Installation

Open a **new** terminal:

```bash
node -v
npm -v
```

---

## 6️⃣ Install MySQL

### Windows

#### Option A: Using XAMPP (If you installed XAMPP for PHP)

MySQL is already included! Just:

1. Open XAMPP Control Panel
2. Click **Start** next to **MySQL**
3. MySQL will run on `localhost:3306`

#### Option B: Standalone MySQL Installation

1. Download MySQL Installer from: https://dev.mysql.com/downloads/installer/
2. Choose **MySQL Installer (Web)** - smaller download
3. During setup, select:
    - **MySQL Server 8.0**
    - **MySQL Workbench** (optional, for GUI management)
4. Set a root password (remember this!)
5. Complete the installation

### Mac

**Using Homebrew:**

```bash
brew install mysql
brew services start mysql
```

### Create the Database

1. Open a terminal and connect to MySQL:

    **Windows (XAMPP):**

    ```powershell
    C:\xampp\mysql\bin\mysql -u root
    ```

    **Windows (Standalone) or Mac:**

    ```bash
    mysql -u root -p
    ```

2. Create the database:
    ```sql
    CREATE DATABASE paghupay;
    EXIT;
    ```

---

## 7️⃣ Set Up SendGrid (Email Service)

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

## 8️⃣ Get the Project Code

You have two options to get the project: **Clone from GitHub** (recommended) or extract from a ZIP file.

### Option A: Clone from GitHub (Recommended)

#### Step 1: Open VS Code

1. Open VS Code
2. Open the integrated terminal: **Terminal** → **New Terminal** (or press `` Ctrl+` ``)

#### Step 2: Navigate to Your Projects Folder

First, navigate to where you want to store the project.

**Windows:**

```powershell
# Create a projects folder (if it doesn't exist)
mkdir D:\projects

# Navigate to it
cd D:\projects
```

**Mac:**

```bash
# Create a projects folder (if it doesn't exist)
mkdir ~/projects

# Navigate to it
cd ~/projects
```

#### Step 3: Clone the Repository

```bash
git clone https://github.com/Kunehochii/paghupay.git
```

This will create a `paghupay` folder with all the project files.

#### Step 4: Open the Project in VS Code

**Windows:**

```powershell
cd paghupay
code .
```

**Mac:**

```bash
cd paghupay
code .
```

This opens the project folder in a new VS Code window.

### Option B: Extract from ZIP File

If you received the project as a ZIP file:

1. Extract the ZIP to your desired location (e.g., `D:\projects\paghupay`)
2. Open VS Code
3. Go to **File** → **Open Folder**
4. Select the extracted `paghupay` folder

---

## 9️⃣ Project Setup (Inside VS Code)

Now that you have the project open in VS Code, let's set it up.

### Opening the Terminal in VS Code

1. In VS Code, go to **Terminal** → **New Terminal** (or press `` Ctrl+` ``)
2. The terminal opens at the bottom of VS Code, already in the project folder

### Navigating to the Project (If Needed)

If your terminal is not in the project folder:

**Windows:**

```powershell
# Check your current location
pwd

# Navigate to the project
cd D:\projects\paghupay
```

**Mac:**

```bash
# Check your current location
pwd

# Navigate to the project
cd ~/projects/paghupay
```

### Step 1: Install PHP Dependencies

```bash
composer install
```

This will download all Laravel dependencies. With the `zip` extension enabled, this should take 2-5 minutes instead of 10-15 minutes.

### Step 2: Install Node.js Dependencies

```bash
npm install
```

### Step 3: Create Environment File

**Windows (PowerShell):**

```powershell
copy .env.example .env
```

**Mac/Linux:**

```bash
cp .env.example .env
```

### Step 4: Configure the `.env` File

1. In VS Code's file explorer (left sidebar), find and click on `.env`
2. Update these values:

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

### Step 5: Generate Application Key

```bash
php artisan key:generate
```

This generates a unique encryption key for your application.

### Step 6: Run Database Migrations

This creates all the database tables:

```bash
php artisan migrate
```

### Step 7: Seed the Database (Optional)

To add default time slots:

```bash
php artisan db:seed
```

### Step 8: Create Storage Link

This enables file uploads to work correctly:

```bash
php artisan storage:link
```

---

## 🔟 Running the Application

You need to run **two terminals** simultaneously in VS Code.

### Opening Multiple Terminals in VS Code

1. Open the first terminal: **Terminal** → **New Terminal**
2. To open a second terminal, click the **+** icon in the terminal panel, or go to **Terminal** → **New Terminal** again
3. You can switch between terminals using the dropdown in the terminal panel

### Terminal 1: PHP Development Server

In the first terminal:

```bash
php artisan serve
```

This starts the backend server at `http://localhost:8000`

**Keep this terminal running!**

### Terminal 2: Vite Development Server (Frontend Assets)

In the second terminal:

```bash
npm run dev
```

This compiles CSS/JavaScript and enables hot-reloading.

**Keep this terminal running too!**

### Access the Application

Open your browser and go to:

-   **Student Login**: http://localhost:8000/login
-   **Counselor Login**: http://localhost:8000/counselor/login
-   **Admin Login**: http://localhost:8000/admin/login

---

## 1️⃣1️⃣ Creating Test Users

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

-   Press `Ctrl + C` (Windows) or `Cmd + C` (Mac) in each terminal to stop the servers
-   To stop MySQL:
    -   **XAMPP**: Open XAMPP Control Panel and click **Stop** next to MySQL
    -   **Mac (Homebrew)**: Run `brew services stop mysql`

---

## 📂 VS Code Tips

### Useful Keyboard Shortcuts

| Action               | Windows        | Mac           |
| -------------------- | -------------- | ------------- |
| Open terminal        | `` Ctrl+` ``   | `` Cmd+` ``   |
| Open command palette | `Ctrl+Shift+P` | `Cmd+Shift+P` |
| Open file explorer   | `Ctrl+Shift+E` | `Cmd+Shift+E` |
| Search in files      | `Ctrl+Shift+F` | `Cmd+Shift+F` |
| Go to file           | `Ctrl+P`       | `Cmd+P`       |
| Save file            | `Ctrl+S`       | `Cmd+S`       |
| Toggle sidebar       | `Ctrl+B`       | `Cmd+B`       |

### Recommended Settings

Add these to your VS Code settings for a better Laravel experience:

1. Press `Ctrl+Shift+P` (or `Cmd+Shift+P` on Mac)
2. Type "Preferences: Open Settings (JSON)"
3. Add these settings:

```json
{
    "files.associations": {
        "*.blade.php": "blade"
    },
    "emmet.includeLanguages": {
        "blade": "html"
    }
}
```

---

## 📞 Need Help?

If you encounter issues not covered in this guide:

1. Check the Laravel documentation: https://laravel.com/docs
2. Search the error message on Google or Stack Overflow
3. Contact your instructor or project supervisor

---

**GitHub Repository**: https://github.com/Kunehochii/paghupay

_Last Updated: January 2026_
