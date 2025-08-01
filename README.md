<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## ✅ Inventory POS — Installation Guide

Thank you for purchasing Inventory POS by CupaTechHub.
This guide will help you install and set up the system on your Windows machine.
## 🧰 Prerequisites
Please ensure your machine has the following installed:
- PHP ≥ 8.1
- MySQL (e.g., XAMPP or WAMP)
- Composer
- Node.js + npm
Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Step 1: Extract and Open the Project

 - Extract the zip file provided.

 - Open the project folder in your terminal or CMD.

 - Copy .env.client and rename it to .env.
  
  copy .env.client .env
## ⚙️ Step 2: Create the Database
- Open phpMyAdmin or MySQL client.

- Create a new database — you can name it anything (e.g., inventory_pos).

- In .env, update this section:
   DB_DATABASE=inventory_pos       # the name you just created
   DB_USERNAME=root                # or your MySQL username
   DB_PASSWORD=                    # your MySQL password (if any)

### 🔑 Step 3: Generate App Key
In the project terminal, run:
   php artisan key:generate

## 🛠️ Step 4: Install Dependencies
Run the following to install PHP and JS dependencies:
1. Copy .env.client to .env
2. Edit DB_DATABASE, DB_USERNAME, DB_PASSWORD to match your MySQL
3. Run:
   - composer install
   - php artisan key:generate
   - php artisan migrate --seed
   - php artisan storage:link
   - npm install      (only if you need to rebuild assets)
   - npm run build    (optional – assets already compiled)
 - composer install
 - npm install
 - npm run build

## 🧱 Step 5: Migrate the Database

This will create all tables and insert default data (including 1 admin account):
 php artisan migrate --seed
## 👤 Step 6: Login to the System

Start the Laravel server:
  php artisan serve
  Go to http://localhost:8000

Login using the default admin credentials:

  Email:    admin@gmail.com.com
  Password: admin@123
