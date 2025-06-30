## Overview

This project is a Job Portal backend API built with Laravel, supporting roles like Admin, Employer, and Candidate. It allows job listings, applications, skill management, and background job matching.

---

## Setup Instructions

### Prerequisites

- PHP >= 8.x  
- Composer  
- PostgreSQL database   

### Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/RaRashed/job_portal.git
   cd job-portal

2. Install PHP dependencies
   ```bash
   composer install
   ```
3. Copy the environment file
   ```bash
   cp .env.example .env
   ```
4 . Configure your .env
 ```bash
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=jobportal
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```
5 . Generate application key
  ```bash
php artisan key:generate
```
6 . Run database migrations
   ```bash
php artisan migrate
```
7. Start the local server
   ```bash
   php artisan serve
   ```
8. Run the Scheduler task
    ```bash
    php artisan schedule:work
    ```
    For productions
    ```bash
   * * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
   ```

   ## Design Choices

### 1. **Role-Based Access Control**
Implemented Laravel middleware to restrict access based on user roles (`admin`, `employer`, `candidate`). This ensures that each type of user can only access the appropriate resources:
- Employers can post, update, and view their jobs and received applications.
- Candidates can browse jobs, apply, and manage their skills.
- Admins have visibility into platform metrics and maintenance tasks.

### 2. **Modular Architecture**
Separated concerns into:
- **Controllers**: Handle HTTP requests and responses.
- **Models**: Represent the database structure.
- **Jobs/Commands**: For background processing and scheduled tasks.
- **Middleware**: For request filtering and role verification.

This follows the **Single Responsibility Principle** (SRP) from SOLID design.

### 3. **Eloquent Relationships & Pivot Tables**
Used Laravel’s Eloquent ORM for managing relationships:
- `candidate_skill`: Many-to-many between candidates and skills.
- `job_skill`: Many-to-many between jobs and required skills.
This allows flexible querying and simplifies the matching logic.

### 4. **Caching with Redis**
Cached frequently accessed data:
- Recent job listings (`5-minute` expiry).
- Employer application stats.
This improves performance and reduces DB load.

### 5. **Background Job Matching**
Implemented a background job (`MatchCandidatesToJobs`) that:
- Compares job skill requirements with candidate skills.
- Considers salary and location preferences.
- Queues/logs notifications when matches are found.

### 6. **Security Best Practices**
- Used Laravel’s request validation to prevent SQL Injection and XSS.
- Applied CSRF protection (automatically in Laravel).
- Added **rate limiting** on login and apply endpoints to prevent abuse.


### **PostMan Collections and ERD 
https://drive.google.com/drive/folders/1spCzbVyCKiQoolQjJ2sPoyi5JqQqa-CY
```bash
https://drive.google.com/drive/folders/1spCzbVyCKiQoolQjJ2sPoyi5JqQqa-CY
```
