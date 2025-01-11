# Eric Nabofa's To-Do App

Welcome to the **Eric Nabofa To-Do App**, a simple and intuitive task management application built with Laravel. This application is designed to help users organize and track their tasks efficiently.

## Table of Contents

- [Features](#features)
- [Directory Structure](#directory-structure)
- [Installation](#installation)
- [Usage](#usage)
- [Configuration](#configuration)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

## Features

- Add, update, and delete tasks
- Mark tasks as completed
- Persistent storage using MySQL
- Clean and modular MVC architecture
- Blade templating for dynamic and reusable views

## Directory Structure

Here is an overview of the project's key directories:

```
ericnabofa-toDo_App/
├── app/                # Application core logic
│   ├── Http/          # Controllers and middleware
│   ├── Models/        # Eloquent models
│   └── Providers/     # Service providers
├── bootstrap/          # Application bootstrap files
├── config/             # Configuration files
├── database/           # Migrations and seeders
├── public/             # Publicly accessible assets and entry point
├── resources/          # Blade views, CSS, and JS assets
├── routes/             # Application routes
├── storage/            # Storage for logs, sessions, and cache
├── tests/              # Unit and feature tests
└── vendor/             # Composer dependencies
```

## Installation

Follow these steps to set up the project locally:

### Prerequisites

- PHP 8.1 or later
- Composer
- MySQL
- Node.js and npm

### Steps

1. **Clone the repository**:

   ```bash
   git clone https://github.com/ericnabofa/toDo_App.git
   cd toDo_App
   ```

2. **Install dependencies**:

   ```bash
   composer install
   npm install
   npm run dev
   ```

3. **Set up the environment file**:

   Copy the example `.env` file and adjust the configuration:

   ```bash
   cp .env.example .env
   ```

   Update the following fields in `.env`:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

4. **Run migrations**:

   Create the necessary database tables:

   ```bash
   php artisan migrate
   ```

5. **Serve the application**:

   Start the Laravel development server:

   ```bash
   php artisan serve
   ```

   The app will be accessible at [http://localhost:8000](http://localhost:8000).

## Usage

- Navigate to `/tasks` to view the list of tasks.
- Use `/tasks/create` to add a new task.
- Update or delete tasks directly from the list view.

## Configuration

- All application settings can be adjusted in the `.env` file.
- Caching and session drivers are configurable in `config/cache.php` and `config/session.php`.

## Testing

Run the included tests to ensure everything works as expected:

```bash
php artisan test
```


## **📞 Contact**  

**Oghenevwegba Eric Nabofa**  
- **LinkedIn:** [linkedin.com/in/oghenevwegbaenabofa](https://www.linkedin.com/in/oghenevwegbaenabofa/)  


