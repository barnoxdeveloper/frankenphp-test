# FrankenPHP Server Benchmarking & Stress Test Tool

This Laravel application has been configured as a Server Benchmarking & Stress Test tool specifically optimized for FrankenPHP.

## Features

- **Laravel Pulse Dashboard**: Real-time application performance monitoring at `/pulse`
- **Stress Test Endpoint**: CPU, Database I/O, and Memory stress testing at `/stress-test`

## Endpoints

### `/stress-test`

Executes a comprehensive stress test including:

- **CPU Stress**: 15 iterations of `Hash::make('benchmark-password')`
- **Database I/O**: Insert 50KB payload and retrieve latest 50 records
- **Memory Stress**: Generate 10,000 dummy objects (~5-10MB RAM)

**Response JSON:**

```json
{
    "app_name": "Laravel",
    "db_connection_used": "mysql",
    "execution_time": "123.45ms",
    "current_memory_usage": "5.23MB",
    "records_retrieved": 50
}
```

### `/pulse`

Laravel Pulse dashboard for real-time performance monitoring (no authentication required for benchmarking).

## Database Configuration

To switch between MySQL and PostgreSQL for different app instances, configure the `.env` file as follows:

### MySQL Configuration

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=frankenphp_benchmark
DB_USERNAME=benchmark_user
DB_PASSWORD=your_password
```

### PostgreSQL Configuration

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=frankenphp_benchmark
DB_USERNAME=benchmark_user
DB_PASSWORD=your_password
```

## Multiple App Instances Configuration

For running 5 different app instances with different databases, create separate `.env` files:

### Instance 1 (MySQL)

```env
APP_NAME=FrankenPHP-App1
APP_ID=app-1
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=frankenphp_benchmark_1
DB_USERNAME=benchmark_user
DB_PASSWORD=your_password
```

### Instance 2 (PostgreSQL)

```env
APP_NAME=FrankenPHP-App2
APP_ID=app-2
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=frankenphp_benchmark_2
DB_USERNAME=benchmark_user
DB_PASSWORD=your_password
```

### Instance 3 (MySQL)

```env
APP_NAME=FrankenPHP-App3
APP_ID=app-3
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=frankenphp_benchmark_3
DB_USERNAME=benchmark_user
DB_PASSWORD=your_password
```

### Instance 4 (PostgreSQL)

```env
APP_NAME=FrankenPHP-App4
APP_ID=app-4
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=frankenphp_benchmark_4
DB_USERNAME=benchmark_user
DB_PASSWORD=your_password
```

### Instance 5 (MySQL)

```env
APP_NAME=FrankenPHP-App5
APP_ID=app-5
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=frankenphp_benchmark_5
DB_USERNAME=benchmark_user
DB_PASSWORD=your_password
```

## Running the Application

### Using FrankenPHP

```bash
php frankenphp run
```

### Using PHP Built-in Server (for testing)

```bash
php artisan serve
```

## Running Migrations

After configuring the database, run migrations to create the required tables:

```bash
php artisan migrate
```

## Stress Test Table Schema

The `stress_logs` table stores benchmark data with the following columns:

- `id` - Primary key
- `app_id` - Application identifier (from `APP_ID` env variable)
- `db_type` - Database type (mysql or pgsql)
- `payload` - 50KB random string for I/O testing
- `created_at` - Timestamp
- `updated_at` - Timestamp

## Notes

- Laravel Pulse is configured without authentication for benchmarking purposes
- The stress test endpoint is designed to generate significant CPU, memory, and database load
- Each stress test execution inserts a new record to the `stress_logs` table
- The application is compatible with both MySQL and PostgreSQL database drivers
