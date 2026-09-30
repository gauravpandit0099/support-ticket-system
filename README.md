# Support Ticket System

A production-minded Laravel application for managing employee support
ticket requests, administrative review, ticket assignment, employee
management, and ticket progress in a multi-tenant company environment.

## 1. Project Overview

The application has two roles:

-   **Admin** --- manages ticket requests, reviews requests, manages
    tickets, assigns tickets, and manages employees.
-   **Employee** --- submits ticket requests, views their own requests,
    views assigned tickets, and updates assigned ticket status.

### Main workflow

``` text
Employee submits ticket request
        ↓
Admin reviews request
        ↓
Approved?
   ┌────┴────┐
   │         │
 Reject    Approve
   │         │
   ↓         ↓
Rejected   Ticket created
             ↓
        Admin assigns employee
             ↓
        Employee works on ticket
             ↓
      Employee updates status
             ↓
          Resolved
```

## 2. Technology Stack

-   PHP 8.3+
-   Laravel 13
-   MySQL
-   Laravel Blade
-   Tailwind CSS
-   Vite
-   PHPUnit
-   Laravel session authentication
-   Custom role middleware

The application intentionally uses Blade rather than a separate SPA
frontend because the required workflow is primarily CRUD and form based.

## 3. Architecture

The project follows this request flow:

``` text
Browser / API Request
        ↓
Form Request
        ↓
Controller
        ↓
Service
        ↓
Model / Database
        ↓
Service Result
        ↓
Controller
        ↓
JSON Response / Redirect / Blade View
```

### Responsibilities

**Form Requests** - Validate request data. - Authorize the relevant
role. - Provide custom validation messages.

**Controllers** - Receive validated input. - Call services. - Return
Blade views, redirects, or JSON responses. - Do not contain business
logic.

**Services** - Contain business logic. - Perform database operations. -
Apply tenant and ownership restrictions. - Return standardized
results. - Handle unexpected exceptions.

**Models** - Define database relationships. - Define fillable
attributes. - Handle attribute casting.

**Middleware** - Protect admin routes. - Protect employee routes.

Policies and unnecessary repository/interface layers were intentionally
not introduced because they are not required for the current scope.

## 4. Multi-Tenant Design

The application uses a shared database with tenant-based isolation.

Tenant-sensitive records contain a `tenant_id`.

Queries are scoped using the authenticated user's tenant:

``` php
where('tenant_id', Auth::user()->tenant_id)
```

Ownership restrictions are also applied where required.

Examples:

-   Employees can only see their own ticket requests.
-   Employees can only see tickets assigned to them.
-   Admins can only manage records belonging to their tenant.
-   Admins can only assign tickets to employees from their tenant.

Cross-tenant access is covered by feature tests.

## 5. Roles

The system intentionally has two roles:

``` text
admin
employee
```

No separate roles table is used because the current requirements only
need two fixed roles.

### Admin

Admins can:

-   View the admin dashboard.
-   View ticket requests.
-   Approve or reject ticket requests.
-   View created tickets.
-   Assign tickets.
-   Edit ticket details.
-   Update ticket status.
-   Create employees.
-   View employees.
-   Update employees.

### Employee

Employees can:

-   View the employee dashboard.
-   Create ticket requests.
-   View their own ticket requests.
-   Edit eligible ticket requests.
-   View assigned tickets.
-   View assigned ticket details.
-   Update assigned ticket status.

## 6. Database Structure

### tenants

``` text
id
name
created_at
updated_at
```

### users

``` text
id
tenant_id
name
email
mobile
password
role
created_at
updated_at
```

### ticket_requests

``` text
id
tenant_id
created_by
title
description
priority
status
reviewed_by
reviewed_at
rejection_reason
created_at
updated_at
```

Request priorities:

``` text
low
medium
high
urgent
```

Request statuses:

``` text
pending
approved
rejected
```

### tickets

``` text
id
tenant_id
request_id
created_by
assigned_to
title
description
priority
status
created_at
updated_at
```

Ticket statuses:

``` text
open
in_progress
resolved
```

`request_id` is unique so one approved request cannot create multiple
tickets.

## 7. Ticket Request Workflow

An employee creates a request containing:

-   Title
-   Description
-   Priority

A new request starts as:

``` text
pending
```

An admin can approve or reject the request.

### Approval

When approved:

1.  The request becomes `approved`.
2.  The reviewing admin is stored.
3.  The review timestamp is stored.
4.  A ticket is automatically created.
5.  The new ticket starts as `open`.

Approval and ticket creation are performed inside a database
transaction.

### Rejection

When rejected:

``` text
status = rejected
reviewed_by = current admin
reviewed_at = current time
rejection_reason = supplied reason
```

No ticket is created for a rejected request.

## 8. Ticket Assignment

Admins can assign an open ticket to an employee.

The employee must belong to the same tenant as the authenticated admin
and must have the `employee` role.

This prevents cross-tenant assignment.

## 9. Employee Ticket Workflow

Employees can only access tickets where:

``` text
tenant_id = employee tenant
assigned_to = authenticated employee
```

Employees can update assigned ticket status using:

``` text
open
in_progress
resolved
```

Employees cannot update another employee's ticket.

## 10. Employee Management

The User Management module is implemented as an employee-focused admin
module.

Admins can:

-   List employees/users in their tenant.
-   Create employees.
-   Edit employees.

### Create employee

The admin supplies:

-   Name
-   Email
-   Mobile
-   Password

The role is restricted to:

``` text
employee
```

The tenant ID is not accepted from the request. It is automatically
taken from the authenticated admin.

### Update employee

Admins can update:

-   Name
-   Email
-   Mobile
-   Password

Password is optional during updates. If no password is supplied, the
existing password remains unchanged.

The current employee's existing email is allowed during update while
duplicate emails remain rejected.

## 11. Validation

Validation is handled through Laravel Form Requests.

Validation covers:

-   Required fields.
-   Email format.
-   Email uniqueness.
-   Password length.
-   Allowed priorities.
-   Allowed request statuses.
-   Allowed ticket statuses.
-   Valid employee assignment.
-   Tenant-scoped employee selection.

Controllers do not contain validation rules.

## 12. Authorization

Custom middleware protects the two application areas:

``` text
admin
employee
```

Unauthorized role access returns HTTP `403`.

Services additionally apply tenant and ownership conditions when
accessing records.

This gives the application two levels of protection:

``` text
Route role protection
        +
Tenant / ownership protection
```

## 13. Error Handling

Services use `try/catch` blocks for business operations and report
unexpected exceptions.

Service methods return a consistent structure:

``` php
[
    'success' => true,
    'message' => 'Operation completed successfully.',
    'data' => $data,
    'status' => 200,
]
```

Errors use the same structure with an appropriate status code.

## 14. HTTP Responses

The application uses meaningful HTTP status codes:

``` text
200  Successful operation
201  Resource created
401  Authentication failure
403  Unauthorized role access
404  Resource not found
422  Validation failure
500  Unexpected server error
```

Browser requests use redirects and Blade views.

Requests expecting JSON receive JSON responses.

## 15. Project Structure

Important application files are organized as follows:

``` text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   └── AuthController.php
│   │   ├── Admin/
│   │   │   ├── AdminController.php
│   │   │   ├── EmployeeController.php
│   │   │   ├── TicketRequestController.php
│   │   │   └── AdminTicketController.php
│   │   └── User/
│   │       ├── UserController.php
│   │       ├── TicketRequestController.php
│   │       └── TicketController.php
│   │
│   ├── Requests/
│   │   ├── Auth/
│   │   │   └── AuthRequest.php
│   │   ├── Admin/
│   │   │   ├── EmployeeRequest.php
│   │   │   ├── AdminTicketRequest.php
│   │   │   ├── AdminTicketStatusRequest.php
│   │   │   └── AdminTicketUpdateRequest.php
│   │   └── User/
│   │       ├── TicketRequestRequest.php
│   │       └── UserTicketStatusRequest.php
│   │
│   └── Middleware/
│       ├── AdminMiddleware.php
│       └── EmployeeMiddleware.php
│
├── Models/
│   ├── Tenant.php
│   ├── User.php
│   ├── TicketRequest.php
│   └── Ticket.php
│
└── Services/
    ├── Auth/
    │   └── AuthService.php
    ├── Admin/
    │   ├── AdminService.php
    │   ├── EmployeeService.php
    │   ├── TicketRequestService.php
    │   └── AdminTicketService.php
    └── User/
        ├── UserService.php
        ├── TicketRequestService.php
        └── TicketService.php
```

## 16. Frontend

The frontend uses Laravel Blade and Tailwind CSS.

The application has separate Admin Portal and User Portal interfaces.

### Admin navigation

``` text
Dashboard
Ticket Requests
Support Tickets
User Management
```

### Employee navigation

``` text
Dashboard
Ticket Requests
Support Tickets
```

The UI uses consistent forms, tables, status indicators, validation
feedback, and workflow actions.

## 17. Routes

Main browser routes include:

``` text
/login
/logout

/admin/dashboard

/admin/ticket-requests
/admin/ticket-requests/{id}
/admin/ticket-requests/{id}/review

/admin/tickets
/admin/tickets/{id}/assign
/admin/tickets/{id}/edit
/admin/tickets/{id}
/admin/tickets/{id}/status

/admin/users
/admin/users/create
/admin/users/{id}/edit
/admin/users/{id}

/user/dashboard

/user/ticket-requests
/user/ticket-requests/create
/user/ticket-requests/{id}/edit

/user/tickets
/user/tickets/{id}
/user/tickets/{id}/status
```

Routes are protected by the appropriate middleware.

## 18. Testing

The project uses PHPUnit feature tests.

Important test coverage includes:

### Employee ticket requests

-   Employee can create a request.
-   Employee can view their requests.
-   Employee cannot access another employee's request.
-   Employee cannot access another tenant's request.
-   Invalid request data is rejected.

### Admin ticket requests

-   Admin can view requests.
-   Admin can review requests.
-   Requests are tenant scoped.
-   Approved requests create tickets.
-   Rejected requests do not create tickets.
-   Invalid review data is rejected.

### Employee tickets

-   Employee can view assigned tickets.
-   Employee cannot view another employee's ticket.
-   Employee cannot access another tenant's ticket.
-   Employee can update an assigned ticket.
-   Invalid ticket status is rejected.

### Admin tickets

-   Admin can view tickets.
-   Admin can assign tickets.
-   Assignment is tenant scoped.
-   Admin can edit ticket details.
-   Admin can update ticket status.
-   Invalid ticket data is rejected.

### Employee management

-   Admin can view employees.
-   Admin can create employees.
-   Created employees belong to the admin's tenant.
-   Admin can update employees.
-   Admin cannot access another tenant's employee.
-   Duplicate email is rejected.
-   Non-admin users cannot access user management.

Run all tests:

``` bash
php artisan test
```

Run the employee management tests:

``` bash
php artisan test tests/Feature/Admin/AdminUserTest.php
```

## 19. Local Setup

### Requirements

-   PHP 8.3+
-   Composer
-   MySQL
-   Node.js and npm

### Install PHP dependencies

``` bash
composer install
```

### Install frontend dependencies

``` bash
npm install
```

### Create environment file

Windows:

``` bash
copy .env.example .env
```

Linux/macOS:

``` bash
cp .env.example .env
```

### Generate application key

``` bash
php artisan key:generate
```

### Configure database

Update `.env`:

``` env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=support_ticket_system
DB_USERNAME=root
DB_PASSWORD=
```

Create the database in MySQL, then run:

``` bash
php artisan migrate
```

If seed data is configured:

``` bash
php artisan db:seed
```

## Seeded Login Credentials

Run `php artisan db:seed` to create the default tenants and users.

| Tenant | Role | Name | Email | Password |
|---|---|---|---|---|
| Acme Technologies | Admin | Acme Admin | admin@acme.test | password |
| Acme Technologies | Employee | Acme Employee | employee@acme.test | password |
| Globex Corporation | Admin | Globex Admin | admin@globex.test | password |
| Globex Corporation | Employee | Globex Employee | employee@globex.test | password |

### Build frontend

``` bash
npm run build
```

### Start application

``` bash
php artisan serve
```

For Vite development:

``` bash
npm run dev
```

## 20. Test Environment

The test suite uses Laravel's testing environment and `RefreshDatabase`.

Run:

``` bash
php artisan test
```

For detailed test names:

``` bash
php artisan test --testdox
```

## 21. Key Technical Decisions

### Service layer

Business logic is kept in services so controllers remain thin and
reusable.

### Form Requests

Validation and request authorization are kept in Form Requests rather
than controllers.

### Tenant isolation

Tenant filtering is applied explicitly to tenant-sensitive service
queries.

### Database transaction

Request approval and ticket creation occur inside a transaction to avoid
partially completed approval operations.

### Fixed roles

A simple role column is used instead of a permission subsystem because
only two fixed roles are required.

### No repository layer

Repositories and interfaces were not introduced because they would add
abstraction without meaningful benefit for this application's current
size.

### Blade frontend

Blade was selected because the required UI is primarily forms, tables,
and workflow screens. A separate SPA would add unnecessary complexity.

## 22. Assumptions

1.  Each user belongs to one tenant.
2.  Only `admin` and `employee` roles are required.
3.  An approved request creates one ticket.
4.  Only admins review requests.
5.  Only admins assign tickets.
6.  Employees can work only on tickets assigned to them.
7.  Request priority is one of `low`, `medium`, `high`, or `urgent`.
8.  Request status is one of `pending`, `approved`, or `rejected`.
9.  Ticket status is one of `open`, `in_progress`, or `resolved`.
10. Tenant IDs are controlled by the authenticated user context.
11. Session authentication is sufficient for the current browser-based
    application.
12. A separate API authentication package is not required for the
    current scope.

## 23. Scope Exclusions

The following are intentionally outside the current assessment scope:

-   Ticket comments
-   File attachments
-   Email notifications
-   Real-time notifications
-   Complex permission management
-   Billing/payments
-   Analytics
-   Chat
-   Microservices
-   Cloud deployment
-   External notification providers

## 24. Future Improvements

If the application were expanded, possible improvements include:

-   Ticket comments.
-   Attachments.
-   Email and real-time notifications.
-   Ticket activity/audit history.
-   Pagination and advanced filtering.
-   Search.
-   More granular permissions.
-   API versioning and token authentication for external clients.
-   Queue-based notification processing.
-   Additional database indexes based on production query patterns.
-   More explicit ticket state-transition rules.
-   Production monitoring and deployment configuration.

## 25. Production Considerations

For a production deployment, environment settings should include:

``` env
APP_ENV=production
APP_DEBUG=false
```

Application caches can be prepared with:

``` bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

A production environment should also use:

-   HTTPS
-   Secure session configuration
-   Strong database credentials
-   Database backups
-   Application logging and monitoring
-   Proper queue configuration if asynchronous features are added
-   Appropriate web server configuration

## 26. Final Summary

The application implements a complete support-ticket workflow:

``` text
Authentication
     ↓
Role-based access
     ↓
Employee ticket request
     ↓
Admin review
     ↓
Approval / rejection
     ↓
Automatic ticket creation
     ↓
Employee assignment
     ↓
Employee work
     ↓
Status updates
     ↓
Resolution
```

The implementation keeps responsibilities separated across:

``` text
Form Requests
      ↓
Controllers
      ↓
Services
      ↓
Models
      ↓
Database
```

The resulting application is intentionally focused, maintainable,
testable, tenant-aware, and extensible without introducing unnecessary
architectural complexity.
