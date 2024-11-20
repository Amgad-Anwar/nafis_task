Nafis Task
Description

This project is a Laravel application designed to manage tasks with authentication and role-based access control. The application includes the following features:

    Roles: Two user roles, Admin and User.
    Admin Features: Admins can create, update, and assign tasks to users.
    User Features: Users can view assigned tasks.
    Task Assignment: Tasks are assigned to users through a many-to-many relationship between users and tasks.
    Authentication: You can register a new account or use the default admin account:
        Email: admin@gmail.com
        Password: 123456789

Installation
Step 1: Clone the Project

Run the following command to clone the repository:

git clone https://github.com/Amgad-Anwar/nafis_task.git  
cd nafis_task  

Step 2: Install Dependencies

Install the necessary PHP dependencies:

composer install  

Step 3: Configure the Database

    Create a database in your preferred database management system (e.g., MySQL).


Open the .env file and configure the database connection details:

    DB_CONNECTION=mysql  
    DB_HOST=127.0.0.1  
    DB_PORT=3306  
    DB_DATABASE=your_database_name  
    DB_USERNAME=your_username  
    DB_PASSWORD=your_password  

Step 4: Run Migrations

Run the following command to create database tables:

php artisan migrate  

Step 5: Seed the Database

Seed the database with default data, including the default admin account:

php artisan db:seed  

Running Tests

Ensure everything works correctly by running tests:

php artisan test  

Task Scheduling

Run the following command to execute scheduled tasks (if applicable):

php artisan schedule:run  
