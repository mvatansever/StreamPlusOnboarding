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

## Additional Tools and Libraries

This project utilizes several important tools and libraries:

- **Symfony**: A PHP framework for building web applications.
- **Doctrine**: An ORM (Object-Relational Mapping) library for PHP to manage database interactions.
- **PHPUnit**: A testing framework for PHP, used for unit testing.
- **PHPStan**: A static analysis tool for PHP, used for detecting potential issues in the codebase.
- **Symfony Webpack Encore**: A tool to simplify the integration of Webpack into Symfony applications for asset management.

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

### Key Changes:
1. **Additional Tools and Libraries Section**: This new section lists the additional libraries and tools used in the project, providing clarity on the tech stack.
2. **Formatting**: I've added code block syntax for clarity and fixed the bullet points under the steps.

Feel free to modify the content to better suit your project’s specifics!

## You can use the development tools
- You can see the PHPStan static analyse check: `make phpstan-check`
- You can CS Fixer static analyse check: `make code-fixer-fix`