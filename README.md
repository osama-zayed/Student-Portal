# Saba University Student Portal

![Saba University Student Portal](https://osamazayed.com/images/portfolio-5.webp)

## Overview

The Saba University Student Portal is an integrated system designed to manage university operations efficiently. It provides a comprehensive platform that facilitates communication and administrative tasks for students and faculty.

### Key Features

- **College Administration**: 
  - Manages college operations, including specializations, students, professors, and curricula, enhancing communication and follow-up.

- **API**: 
  - Displays comprehensive information about the university, majors, and courses for easy access.

- **Student Section**: 
  - Provides study schedules, results, and achievements, enabling students to track their academic performance effectively.

## Requirements

- **PHP Version**: 
  - PHP >= 8.2

## Installation

To install the Student Portal project, follow these steps:

1. Clone the project repository from GitHub:
   ```bash
   git clone https://github.com/osama-zayed/Student-Portal.git
   ```

2. Navigate to the downloaded project folder:
   ```bash
   cd Student-Portal
   ```

3. Run the database migration:
   ```bash
   php artisan migrate
   ```

4. Seed the default data into the database:
   ```bash
   php artisan db:seed
   ```

5. Start the local development server:
   ```bash
   php artisan serve
   ```

After starting the server, you can access the project through your browser at `http://localhost:8000`.

### Login Information

- **Username**: admin
- **Password**: 123123123
