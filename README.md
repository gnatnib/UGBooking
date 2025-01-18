# UG Booking 🏢

A modern and efficient room booking system built with Laravel 11 and PHP, designed to streamline the process of managing and reserving rooms within an organization.

![Laravel Version](https://img.shields.io/badge/Laravel-11.36.1-red.svg)
![PHP Version](https://img.shields.io/badge/PHP-8.3.6-blue.svg)
![Node.Js Version](https://img.shields.io/badge/Node.js-20.17.0-green.svg)

## ✨ Features

- **User Authentication & Authorization**
  - Secure login and registration system
  - Role-based access control (SuperAdmin, Admin, Users)
  - Protected routes and middleware implementation

- **Room Management**
  - Comprehensive room listing with detailed information
  - Room categories and types
  - Real-time availability checking
  - Room capacity and facilities information

- **Booking System**
  - Intuitive booking interface
  - Date and time slot selection
  - Conflict prevention system
  - Booking history and status tracking

- **Dashboard**
  - Complete booking oversight
  - Analytics and reporting with chart visualization

## 🚀 Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/ug-booking.git
cd ug-booking
```

2. Install Dependencies
```bash
composer install
npm install
```

3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database in .env
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Run migrations and seeders
```bash
php artisan migrate:fresh --seed
```

6. Start development server
```bash
php artisan serve
```

## 🛠️ Requirements
- PHP >= 8.3.6
- Laravel >= 11.36.1
- Node.js >= 20.17.0
- MySQL >= 8.0.30

## 🤝 Contributing
Contributions are welcomed, make sure to fork and create a new branch first before submitting a pull request!

## 🙏 Acknowledgments
- Laravel Team
- All contributors who helped with the project

## 🖋️ Authors
- [@gnatnib](https://github.com/gnatnib)
- [@RahmanHanifs](https://github.com/RahmanHanifs)
- [@FarrelIrbah](https://github.com/FarrelIrbah)
- [@MuhammadRajaFadhilHabibi](https://github.com/MuhammadRajaFadhilHabibi)


## ⭐ Kindly give this repository a star!
