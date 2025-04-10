# Traffic Violation System

[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A web-based application for managing traffic violations, built using PHP and MySQL. This system allows administrators, traffic officers, and users to interact with violation records.

## Features

* **Admin Dashboard:**
    * Manage users (view, add, edit, delete).
    * Manage officers (view, add, edit, delete).
    * View all recorded violations.
* **Officer Dashboard:**
    * Record new traffic violations.
    * View recorded violations.
* **User Dashboard:**
    * View their recorded traffic violations.
    * Simulated payment of fines.
* **User and Officer Registration:** Secure registration process for new users and officers.
* **Login System:** Secure login for different user roles (admin, officer, user).
* **Modern Dark Theme:** A user-friendly dark theme for improved readability.

## Technologies Used

* PHP
* MySQL
* HTML
* CSS
* JavaScript (potentially for minor UI enhancements)

## Setup (for Local Development - Adjust for Deployment)

1.  **Clone the repository (if you haven't already):**
    ```bash
    git clone [https://github.com/your_username/traffic-violation-system.git](https://github.com/your_username/traffic-violation-system.git)
    cd traffic-violation-system
    ```
    *(Replace `https://github.com/your_username/traffic-violation-system.git` with your repository URL)*

2.  **Set up your local development environment (e.g., MAMP on macOS):**
    * Ensure PHP and MySQL are running.
    * Create a new database in MySQL (e.g., `traffic_violation_system`).
    * Import the database schema (you might need to create a `.sql` file from your local database or provide instructions on how to create the tables).

3.  **Configure Database Connection:**
    * Edit the `db.php` file to match your local MySQL database credentials:
        ```php
        <?php
        $servername = "localhost";
        $username = "your_mysql_username"; // e.g., "root"
        $password = "your_mysql_password"; // e.g., "root" or ""
        $dbname = "traffic_violation_system";
        ?>
        ```

4.  **Access the application in your browser:**
    * Open your web browser and navigate to the appropriate URL for your local development server (e.g., `http://localhost:8888/traffic-violation-system/` if using MAMP with default port 8888).

## Live Demo

*[Link to a live demo of the application, if you have it deployed on a public server]*

## Screenshots

*[Consider adding screenshots of the different dashboards (admin, officer, user), login page, violation listing, etc. You can add these as image files in a `screenshots` folder in your repository and then link them here using Markdown syntax like `![Screenshot of Login Page](screenshots/login.png)`]*

## Contributing

*[If you want to allow others to contribute to your project, add guidelines here. For a personal portfolio project, you might omit this section.]*

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Author

[Jatin verma / jatinverma023]
