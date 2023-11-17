# Project Documentation: Achievement System in Laravel

## Overview
This project implements an achievement system in Laravel, designed to track user activities like writing comments and watching lessons. Using an event-driven approach and the repository design pattern, the system provides a robust, scalable, and modular architecture. Additional features include Docker support via Laravel Sail and an integrated mail service.

## Features
- **Achievement Tracking**: Monitors user activities and unlocks achievements based on predefined criteria.
- **Badge System**: Awards badges to users based on the number of achievements unlocked.
- **Event-Driven Architecture**: Utilizes Laravel events to handle achievement unlocking and badge awarding.
- **Repository Design Pattern**: Offers flexibility and modularity, making future expansions more manageable.
- **Docker Support**: Simplifies local setup and ensures consistency across different environments using Laravel Sail.
- **Mail Service Integration**: Verifies that mail-related events are triggered and handled correctly.

## Setup and Local Development

### Prerequisites
- Docker and Docker Compose
- PHP >= 8.0
- Composer

### Running the Application Locally

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/your-repository-url.git
   cd your-repository-directory
   ```

2. **Start Laravel Sail**:
   Laravel Sail is a light-weight command-line interface for interacting with Laravel's default Docker development environment. To start the Docker containers for the application, run:
   ```bash
   ./vendor/bin/sail up
   ```

3. **Install Dependencies**:
   While the Docker container is running, install the PHP dependencies:
   ```bash
   ./vendor/bin/sail composer install
   ```

4. **Run Migrations**:
   Set up the database by running the migrations:
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

5. **Seed the Database** (Optional):
   Populate the database with initial data (if seeders are provided):
   ```bash
   ./vendor/bin/sail artisan db:seed --class=SEEDERCLASSNAME
   ```

6. **Access the Application**:
   The application should now be running on `http://localhost`.

### Running Tests

To ensure the reliability of the application, a suite of tests has been provided. Run these tests to verify that all components of the system are functioning as expected:

```bash
./vendor/bin/sail artisan test
```

## Understanding the Achievement System

### Events and Listeners
- **AchievementUnlocked Event**: Fired when a user unlocks an achievement. Payload includes `achievement_name` and `user`.
- **BadgeUnlocked Event**: Fired when a user earns a new badge. Payload includes `badge_name` and `user`.

### Endpoints
- **GET `users/{user}/achievements`**: Returns user achievements and badges, including:
    - `unlocked_achievements`: Array of unlocked achievements by name.
    - `next_available_achievements`: Next achievable milestones.
    - `current_badge`: User's current badge.
    - `next_badge`: Next badge achievable.
    - `remaining_to_unlock_next_badge`: Achievements needed for the next badge.

### Mail Notifications - Mailpit
- Integrated mail notifications confirm the correct functioning of achievement-related events.

## Repository Design Pattern
- Enhances maintainability and scalability.
- Facilitates collaborative development.

## Docker and Laravel Sail
- Ensures a consistent development environment.
- Simplifies the setup process.

---

This project adheres to best practices in Laravel development, offering a robust and scalable solution for achievement tracking.
