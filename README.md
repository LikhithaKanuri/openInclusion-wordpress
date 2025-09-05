# WordPress Local Setup Guide (macOS & Windows) using XAMPP



## Overview
- [1. Introduction](#1-introduction)
- [2. Prerequisites](#2-prerequisites)
- [3. Setup on macOS](#3-setup-on-macos)
  - [3.1 Install XAMPP](#31-install-xampp)
  - [3.2 Copy WordPress Files](#32-copy-wordpress-files)
- [4. Setup on Windows](#4-setup-on-windows)
  - [4.1 Install XAMPP](#41-install-xampp)
  - [4.2 Copy WordPress Files](#42-copy-wordpress-files)
- [5. Database Import (Both macOS & Windows)](#5-database-import-both-macos--windows)
  - [5.1 Create Database](#51-create-database)
  - [5.2 Import SQL Dump](#52-import-sql-dump)
- [6. Configure WordPress](#6-configure-wordpress)
- [7. Run WordPress](#7-run-wordpress)
- [8. Optional Fixes](#8-optional-fixes)
- [Conclusion](#conclusion)


---


## 1. Introduction

This guide explains how to run an existing WordPress site locally using XAMPP on <span style="color:lightblue; font-weight:bold;">macOS</span> and <span style="color:lightblue; font-weight:bold;">Windows</span>.


---

## 2. Prerequisites

- WordPress source code (usually `public_html` folder)
- MySQL database dump (`.sql` file)
- XAMPP installed ([https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html))

---

## 3. Setup on macOS

### 3.1 Install XAMPP

1. Download **XAMPP for macOS** from [https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html)
2. Open the downloaded `.dmg` file and move **XAMPP** to **Applications**.

#### Note: If You Get “App Cannot Be Opened” Error:
- Go to **Apple Menu → System Settings → Privacy & Security**.
- Scroll to **Security** section and you’ll see:  
  *“XAMPP was blocked because it is from an unidentified developer”*  
- Click **Allow Anyway**.
- Re-open XAMPP. You will now see an **Open** button.
- If you still face issues, run this in Terminal:  
  ```bash
  sudo xattr -rd com.apple.quarantine /Applications/XAMPP/XAMPP.app

### 3.2 Copy WordPress Files

```bash
sudo mkdir /Applications/XAMPP/xamppfiles/htdocs/projectname
sudo cp -R ~/Downloads/public_html/* /Applications/XAMPP/xamppfiles/htdocs/projectname/
```

Ensure that `index.php`, `wp-config.php`, `wp-content/` etc., are directly inside `projectname`.

---

## 4. Setup on Windows

### 4.1 Install XAMPP

1. Download **XAMPP for Windows**.
2. Run installer and launch XAMPP Control Panel.
3. Start **Apache** and **MySQL**.

### 4.2 Copy WordPress Files

- Navigate to:
  ```
  C:\xampp\htdocs
  ```
- Create folder `mywordpress`.
- Copy all files from `public_html` into `C:\xampp\htdocs\mywordpress`.

---

## 5. Database Import (Both macOS & Windows)

### 5.1 Create Database

#### macOS:

```bash
/Applications/XAMPP/xamppfiles/bin/mysql -u root -e "CREATE DATABASE db_name;"
```

#### Windows:

```bash
C:\xampp\mysql\bin\mysql -u root -e "CREATE DATABASE db_name;"
```

### 5.2 Import SQL Dump

#### macOS:

```bash
/Applications/XAMPP/xamppfiles/bin/mysql -u root db_name < ~/Downloads/backup.sql
```

#### Windows:

```bash
C:\xampp\mysql\bin\mysql -u root db_name < C:\path\to\backup.sql
```

---

## 6. Configure WordPress

Edit `wp-config.php` in `mywordpress`:

```php
define('DB_NAME', 'db_name');
define('DB_USER', 'root');
define('DB_PASSWORD', ''); // NOTE: If your MySQL root user has a password, add it here.
define('DB_HOST', 'localhost');
```

---

## 7. Run WordPress

### macOS:

```
http://localhost/projectname
```

### Windows:

```
http://localhost/projectname
```

---

## 8. Optional Fixes

### 8.1 Update Site URL (if redirects to old domain)

```bash
UPDATE wp_options SET option_value='http://localhost/projectname'
WHERE option_name IN ('siteurl','home');
```

### 8.2 Fix Permalinks

Dashboard → Settings → Permalinks → **Save Changes**.

### 8.3 Increase Upload Limit

Edit `php.ini`:

```ini
upload_max_filesize = 700M 
post_max_size = 700M
```
> **Note:** Adjust these values based on the size of your SQL dump file. If your dump is 100 MB, you can safely set it to 200M or more.

Restart Apache after changes.

---

## Conclusion

You now have your existing WordPress website running locally on both macOS and Windows using XAMPP.

