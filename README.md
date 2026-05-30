# Blood Donor Finder System

A web-based application to help patients, hospitals, and blood banks quickly locate suitable blood donors.

## Technologies Used
- PHP (Server-side)
- MySQL (Database)
- HTML5
- Tailwind CSS (Styling)
- JavaScript & AJAX (Interactive features)

## Setup Instructions

1. **Import the Database**
   - Create a MySQL database named `blood_donor_finder`
   - Import the `database.sql` file into your database

2. **Configure Database Connection**
   - Open `config.php`
   - Update the database credentials if needed (host, username, password)

3. **Run the Application**
   - Place the project files in your web server's root directory (e.g., htdocs for XAMPP)
   - Start your web server and MySQL
   - Access the application at `http://localhost/`

## Default Admin Login
- Email: admin@example.com
- Password: password

## Features
- Donor registration and profile management
- Secure login system
- Search donors by blood group and location (AJAX-based)
- Admin panel to manage donor records
- Responsive design with Tailwind CSS
