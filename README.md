# 🌿 Leafé Mart

**Leafé Mart** is an online mini-market platform designed for Mahallah Bilal residents and IIUM students. Shop for everyday essentials, snacks, drinks, and more — all from the comfort of your room!

![Laravel](https://img.shields.io/badge/Laravel-12.x-red?logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-purple?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-Database-blue?logo=mysql)
![Livewire](https://img.shields.io/badge/Livewire-3.x-pink?logo=livewire)

---

## 📖 Course Information

| | |
|---|---|
| **Course** | INFO 3305 - Web Application Development |
| **Semester** | Semester 1, 2025/2026 |
| **Section** | 3 |
| **Group** | 4 |
| **Kulliyyah** | Information & Communication Technology (KICT) |

---

## 👥 Team Members

| Name | Matric No. | Assigned Task |
|------|-----------|---------------|
| Ghassan Bin Sharifuddin | 2112819 | Project Scope & Constraints |
| Ahmad Danish Qayyim Bin Azmi | 2310789 | Coding (Views & Models) |
| Naila Saleem | 2312934 | Significance & Coding (Routes & Controllers) |
| Idham Zakwan Bin Mat Yazi | 2318121 | Introduction, Problem & Objectives |
| Muhamad Aqil Ikhwan Bin Ab Rahman | 2215761 | Demo & System Development |

---

## 🔴 Problem Statement

Living in university hostels comes with unique challenges when it comes to shopping for daily essentials:

1. **Limited Access to Shops** - Students in Mahallah Bilal have limited access to nearby convenience stores
2. **Time Constraints** - Busy schedules with classes, assignments, and activities make shopping trips difficult
3. **Inconvenient Shopping Hours** - Physical shops have fixed operating hours
4. **No Centralized Platform** - No dedicated online platform exists for mahallah residents

---

## 🎯 Project Objectives

1. Develop a user-friendly e-commerce platform accessible 24/7
2. Implement a complete shopping system with cart, checkout, and order tracking
3. Provide administrative tools for product and order management
4. Ensure secure and reliable user authentication

---

## 📐 Project Scope

### Customer Features
- User registration and login with email verification
- Browse products by categories (Food, Drinks, Toiletries, Stationery, Medication)
- Search products by name
- View product details with variations (sizes, flavors)
- Shopping cart with quantity management
- Checkout with delivery or self-pickup options
- Order history and status tracking
- Notification system for order updates

### Admin Features
- Dashboard with statistics and activity logs
- Product management (CRUD) with variations
- Category management
- Order management with status updates
- User management
- FAQ management
- Announcement system

---

## ⚠️ Constraints

**Technical Constraints:**
- Limited to PHP/Laravel framework
- Database hosted locally using XAMPP/MySQL
- No payment gateway integration (Cash on Delivery/Pickup only)

**Time Constraints:**
- Development completed within semester timeframe

**Scope Limitations:**
- Designed for single mahallah use
- Web-based only (no mobile app)
- No real-time delivery tracking

---

## 💡 Significance of the Project

**For Students:**
- Convenience — Shop anytime, anywhere
- Time Saving — No need to travel to distant shops
- Easy Access — Browse and order in minutes

**For the Community:**
- Supports local economy within campus
- Creates centralized platform for residents

**Educational Value:**
- Application of MVC architecture
- Real-world CRUD implementation
- Web application development lifecycle

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

## 📸 Screenshots

### Homepage
<img width="1896" height="941" alt="image" src="https://github.com/user-attachments/assets/e05143c1-e5d6-40ca-aa3a-86e36b2d8b8b" />

### Browse Products
<img width="1904" height="945" alt="image" src="https://github.com/user-attachments/assets/75516ee6-9496-4bfa-b555-9c802672718b" />

### Shopping Cart
<img width="1906" height="943" alt="image" src="https://github.com/user-attachments/assets/83de9d06-894c-4777-977e-3f8d6de1725d" />

### Admin Dashboard
<img width="1903" height="911" alt="image" src="https://github.com/user-attachments/assets/ec2dc163-bd64-4809-a73b-aba25601c030" />

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

This project is developed for educational purposes as part of the Web Application Development (INFO 3305) course at IIUM.

---

## 🙏 Acknowledgments

- **International Islamic University Malaysia (IIUM)**
- **Kulliyyah of Information & Communication Technology (KICT)**
- **Laravel Framework**
- **Livewire**
