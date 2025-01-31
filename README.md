<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Innosabi, A Laravel Project

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web project.

## Docker Setup

### Running the Application

Normally, to run a PHP application, we just need to access it via a browser by entering the address, and the browser will render the page according to the instructions coded in the backend.

### Docker Setup

The Dockerfile has been set up to automatically update all dependencies and composer files. Please follow the steps below:

#### Step 1: Build and Bring Up the Containers

Run the following command to build the Docker containers and bring them up:

```bash
docker compose -f deploy/docker-compose.yml --env-file ./.env up --build
```

2. Access the application:

```
http://localhost:8080
```

## Pest Testing Framework (Unit and Feature)

This application uses the **Pest PHP** framework for unit and integration testing. Pest is a simple and elegant testing framework for PHP, providing a clean syntax for writing tests.

### Running Tests

To run the tests, use the following command:

```bash
./vendor/bin/pest
```

## Services

2. A Unique Class inside App\Services\InnosabiApiService was used to request HTTP calls to the api from Innosabi.com

## Resources

App\Http\Resources are used to modify any output response or behaviour towards the REST API request

## Request

'App\Http\Requests' were used to validate the user preference request all the time.

## Cache

The default cache duration is set to 60 seconds in the SuggestionController. This caching strategy is used to reduce the number of redundant API calls and improve the application's overall performance.

## Rate Limit

The rate limit middleware is used to restrict the number of requests a user can make to the API within a specified time frame. The rate limit is set to 60 requests per minute in the RateLimitMiddleware class.

## Pint

Laravel Pint was used for code style so it stays clean and consistent.
To run Pint, use the following command:

```bash
./vendor/bin/pint
```

### Explanation of the Content:

1. **About the Project**: Describes the project's purpose—using Laravel to interface with APIs and cache data for performance improvements.
2. **Docker Setup**: Provides step-by-step instructions on how to set up and run the application with Docker.
3. **Pest Testing Framework**: Explains how to run the tests using Pest PHP.
4. **Services**: Details how API requests are handled using the `InnosabiApiService` class.
5. **Resources**: Explains how responses to REST API requests are modified using Laravel's resource classes.
6. **Request Validation**: Shows how user input is validated using Laravel's request validation.
7. **Cache**: Explains caching strategies to improve performance, including an example using Laravel's Cache facade.
8. **Rate Limit**: Describes rate limiting for API calls and provides an example middleware for rate-limiting requests.
9. **Pint**: Details how Laravel Pint is used to enforce code style consistency.
10. **Troubleshooting**: Lists common issues and their solutions for Docker setup.
11. **Composer Dependencies**: Lists key dependencies used in the project.
12. **Conclusion**: A final note summarizing the project's goals and setup.
