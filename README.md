# StreamPlus Onboarding

This project provides an onboarding process for users, including user info, address, and payment requests.

## Author

**Mesut VATANSEVER** [mesut.vts@gmail.com](mailto:mesut.vts@gmail.com)

## Project Requirements

This project needs the following tools to run properly:

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Composer
- Docker
- Docker Compose

## How to Run the Application

1. Clone the repository:

   ```git clone https://github.com/mvatansever/StreamPlusOnboarding.git```
2. Navigate to the project directory:

   ```cd StreamPlusOnboarding```
3. Ensure Docker and Docker Compose are installed. If not, you can follow the installation instructions on the official Docker documentation.
4. Build the Docker containers:
   `make build`
5. Start the application:
   `make up`
6. Install the composer dependencies
   `make composer-install`
7. Run the migrations
   `make migrate` 
8. Access the application in your web browser at the following URL:
   http://0.0.0.0:8080/onboarding/user-info

## Testing

Run the tests using PHPUnit:

- For unit tests: ```./vendor/bin/phpunit tests```

## Database

The application uses MySQL as the database. Make sure to set the correct credentials in the `docker-compose.yml` file if needed.

## Code Coverage

The project has 100% code coverage for both integration and unit tests.
