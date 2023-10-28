# Laravel User Management App

## Installation 
Make sure you have the environment set up properly. You will need PHP8.1, composer, and Node.js.

1. Download the project (or clone using GIT)/ git clone https://github.com/nilniloy002/usermanagementapp.git
2. Run `composer install`
3. Copy `.env.example` into `.env` and configure database credentials
4. Set the encryption key by executing `php artisan key:generate --ansi`
5. Run migrations `php artisan migrate` OR direct import the database from the project database folder named usermanagedb.sql
6. Start the local server by executing `php artisan serve`

If you existing database usermanagedb.sql then login credentials are 

## Super Admin Credentials
•	Email: admin@example.com
•	Password: admin123
## Admin Credentials
•	Email: hoe.diconlogy@gmail.com
•	Password: 123456789
## User Credentials
•	Email: niloysaha40@gmail.com
•	Password: 123456789

## For the mail server I'm using mailtrap.io
when you use the mail server I must create a free account from mailtrap.io for the Laravel project mailtrap.io gives these types of credentials that you'll use in the .env file.

####################
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=ec8f90c44e6e3c
MAIL_PASSWORD=b99491cafde2de
####################

## Server Requirements
•	PHP >= 8.1.0
•	BCMath PHP Extension
•	OpenSSL PHP Extension
•	PDO PHP Extension
•	Mbstring PHP Extension
•	Tokenizer PHP Extension
•	Ctype PHP Extension
•	XML PHP Extension
•	JSON PHP Extension
•	GD PHP Extension
•	Fileinfo PHP Extension

## Features
•	Secure user registration and login
•	Password reset
•	Two-Factor Authentication
•	Remember Me feature on login
•	Login with email or username
•	Authentication Throttling (lock user account after few incorrect login attempts)
•	Interactive Dashboard
•	Unlimited number of user roles
•	Powerful admin panel
•	Unlimited number of permissions
•	Manage permissions from super admin interface
•	Assign permission to roles
•	Easily check if user has permission to perform some action
•	User Activity Log
•	Avatar upload with crop feature
•	Active Sessions Management (see and manage all your active sessions)
•	Department Wise Employee Management

## Security
•	CSRF Protection – all forms include CSRF token
•	Session Protection – highly secure Laravel session mechanism
•	Highly secure one-way password hashing

