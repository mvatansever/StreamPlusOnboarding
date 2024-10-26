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
4. You can use `make run-all` to cover all the following steps, or you can go one by one:
- `make build`
- `make up`
- `make composer-install`
- `make migrate` 
- `make build-assets`

5. Access the application in your web browser at the following URL:
   http://0.0.0.0:8080/onboarding/user-info

## Testing

Run the tests using PHPUnit:

- For unit tests: ```./vendor/bin/phpunit tests```

## Database

The application uses MySQL as the database. Make sure to set the correct credentials in the `docker-compose.yml` file if needed.

## Code Coverage

The project has 100% code coverage for both integration and unit tests.
