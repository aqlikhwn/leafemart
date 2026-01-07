# 🌿 Leafé Mart

**Leafé Mart** is an online mini-market platform designed for Mahallah Bilal residents and IIUM students. Shop for everyday essentials, snacks, drinks, and more — all from the comfort of your room!

![Laravel](https://img.shields.io/badge/Laravel-12.x-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-purple?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-Database-blue?logo=mysql)
![Livewire](https://img.shields.io/badge/Livewire-3.x-pink?logo=livewire)

---

## ✨ Features

### Customer Features
- 🛒 **Smart Shopping Cart** - Multi-variation support, real-time quantity updates
- 🔍 **Product Search & Browse** - Filter by categories, search by name
- 📦 **Order Management** - Track orders with status filters
- 🔔 **Real-time Notifications** - Livewire-powered notification badges
- 👤 **Profile Management** - Update personal info, change password
- 🔐 **Secure Authentication** - Email verification, password reset

### Admin Features
- 📊 **Dashboard** - Overview with statistics and activity logs
- 📦 **Product Management** - CRUD operations with variations
- 🏷️ **Category Management** - Organize products by category
- 📋 **Order Management** - Update order and payment status
- 👥 **User Management** - View and manage customers
- 📢 **Announcements** - Send notifications to all users

---

## 🛠️ Technology Stack

| Technology | Purpose |
|------------|---------|
| Laravel 12 | Backend Framework |
| PHP 8.2+ | Server-side Language |
| MySQL | Database |
| Blade | Templating Engine |
| Livewire 3 | Real-time Components |
| CSS3 | Styling & Animations |

---

## 📋 Requirements

- PHP >= 8.2
- Composer
- MySQL/MariaDB
- XAMPP/WAMP/Laravel Valet (or similar)
- Node.js (optional, for asset compilation)

---

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/leafemart.git
   cd leafemart
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Setup database**
   - Create a MySQL database named `leafemart`
   - Update `.env` with your database credentials:
     ```
     DB_DATABASE=leafemart
     DB_USERNAME=root
     DB_PASSWORD=
     ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Link storage**
   ```bash
   php artisan storage:link
   ```

7. **Start the server**
   ```bash
   php artisan serve
   ```

8. **Visit the application**
   ```
   http://127.0.0.1:8000
   ```

---

## 👥 Team Members

| Name | Matric No. | Role |
|------|-----------|------|
| [Member 1] | [Matric] | [Role] |
| [Member 2] | [Matric] | [Role] |
| [Member 3] | [Matric] | [Role] |
| [Member 4] | [Matric] | [Role] |

---

## 📸 Screenshots

### Homepage
*Welcome page with featured products carousel and category browsing*

### Browse Products
*Product grid with category filters and search*

### Shopping Cart
*Cart with variation support and quantity controls*

### Admin Dashboard
*Admin panel with statistics and management tools*

---

## 📁 Project Structure

```
leafemart/
├── app/
│   ├── Http/Controllers/     # Controllers
│   ├── Livewire/             # Livewire components
│   └── Models/               # Eloquent models
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── resources/
│   └── views/                # Blade templates
├── routes/
│   └── web.php               # Web routes
└── public/                   # Public assets
```

---

## 📄 License

This project is developed for educational purposes as part of the Web Application Development course at IIUM.

---

## 🙏 Acknowledgments

- **International Islamic University Malaysia (IIUM)**
- **Laravel Framework**
- **Livewire**
