# Hotel Management System

A web-based application designed to streamline operations in hotels, managing reservations, check-ins, check-outs, room management, billing, and more.

## Table of Contents
1. [Introduction](#introduction)
2. [Roles and Permissions](#roles-and-permissions)
3. [Features](#features)
4. [Technologies Used](#technologies-used)
5. [Installation](#installation)
6. [Configuration](#configuration)
7. [Running the Application](#running-the-application)
8. [Usage](#usage)
9. [Contributing](#contributing)
10. [License](#license)
11. [Contact](#contact)

## Introduction

The Hotel Management System provides a comprehensive solution for hotel staff to manage daily operations efficiently, supporting functionalities like room reservations, customer management, and billing.

## Roles and Permissions

The system supports three roles:

- **Admin**: Full access to manage rooms, reservations, user accounts, and view reports.
- **Employee**: Manages room bookings, check-ins, and check-outs with limited access.
- **Customer**: Books rooms, views reservation details, and manages their profile.

## Features
- Room reservation system
- Customer check-in and check-out
- Room management
- Billing and invoicing
- User authentication and authorization

## Technologies Used
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Web Server:** Apache (via XAMPP)
- **Development Tools:** XAMPP

## Installation

### Prerequisites
- XAMPP (includes Apache, MySQL, PHP)
- Git

### Step-by-Step Installation
1. Clone the repository:
    ```bash
    git clone https://github.com/zahi1/hotel-management-system.git
    ```
2. Navigate to the project directory:
    ```bash
    cd hotel-management-system
    ```
3. Move the project to the XAMPP `htdocs` directory:
    ```bash
    mv hotel-management-system /path_to_xampp/htdocs/
    ```

## Configuration

1. Start Apache and MySQL:
    - Open XAMPP Control Panel.
    - Start Apache and MySQL modules.
2. Create the database:
    - Open phpMyAdmin.
    - Create a new database named `hotel_db`.
    - Import the SQL file from the `database` folder.
3. Configure database connection in `config.php` or `.env`:
    ```php
    $db_host = 'localhost';
    $db_user = 'root';
    $db_pass = '';
    $db_name = 'hotel_db';
    ```

## Running the Application

1. Access the application:
    - Open your web browser.
    - Navigate to `http://localhost/hotel-management-system`.
2. Login:
    - Use the provided credentials or register a new account.

## Usage
- **Admin Panel:** Manage rooms, reservations, and user accounts.
- **Employee Portal:** Handle room bookings and customer services.
- **Customer Portal:** Book rooms and manage personal details.

## Contributing

Contributions are welcome! Fork the repository and submit a pull request.

## License

This project is licensed under the MIT License.

## Contact

For any inquiries, please reach out to Zahi El Helou at elhelouzahi@gmail.com.

