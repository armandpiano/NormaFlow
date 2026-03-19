# NormaFlow

**SaaS Platform for Regulatory Compliance Management (NOM/STPS) in Mexico and Latin America**

## Overview

NormaFlow is a comprehensive SaaS platform designed for companies operating in Mexico and Latin America to manage their regulatory compliance requirements, including:

- **NOM/STPS Compliance**: Management of regulatory standards compliance
- **Documentary Evidence**: Centralized document repository and evidence management
- **Internal Audits**: Complete audit planning, execution, and tracking
- **Findings & Action Plans**: Track and resolve compliance findings
- **Training**: Employee training management and certification tracking

## Architecture

NormaFlow follows **Hexagonal Architecture** (Ports & Adapters) with **Clean Architecture** principles:

```
app/
├── Domain/           # Core business logic (framework-agnostic)
│   ├── Companies/    # Company and site management
│   ├── Compliance/   # Regulations, requirements, evidence
│   ├── Identity/     # Users, roles, permissions
│   └── Shared/       # Shared value objects and events
├── Application/      # Use cases, commands, queries, services
│   ├── Commands/     # Write operations (CQRS)
│   ├── Queries/      # Read operations (CQRS)
│   ├── Services/     # Application services
│   └── DTOs/         # Data Transfer Objects
├── Infrastructure/   # External implementations
│   ├── Persistence/  # Database adapters (Doctrine/Eloquent)
│   ├── Storage/      # File storage (S3, local)
│   └── Notifications/# Email, SMS, Push notifications
└── UI/               # Presentation layer
    ├── API/          # REST API controllers
    └── Web/           # Web controllers and views
```

## Tech Stack

- **Backend**: Laravel 11.x (PHP 8.2+)
- **Frontend**: Livewire + Alpine.js (Blade templates)
- **Database**: PostgreSQL (multi-tenant)
- **Cache**: Redis
- **Queue**: Laravel Horizon (Redis)
- **File Storage**: AWS S3
- **Authentication**: Laravel Sanctum (SPA) + Laravel Passport (API)
- **Payments**: Stripe

## Getting Started

### Prerequisites

- PHP 8.2+
- Composer 2.x
- Node.js 20+
- PostgreSQL 15+
- Redis 7+

### Installation

```bash
# Clone the repository
git clone https://github.com/armandpiano/NormaFlow.git

# Install dependencies
composer install
npm install

# Create environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Start the development server
php artisan serve
```

### Docker (Recommended)

```bash
docker-compose up -d
```

## Features

### MVP Features

1. **Multi-tenant Management**
   - Company registration and management
   - Site/branch management
   - Subscription plans (Starter, Growth, Corporate)

2. **User & Access Control**
   - Role-based access control (RBAC)
   - 5 user types: Super Admin, Company Admin, Site Manager, Auditor, Employee
   - Permission management per module

3. **Compliance Module**
   - Regulation catalog (NOM/STPS)
   - Requirements matrix
   - Evidence upload and validation
   - Expiration tracking

4. **Audit Module**
   - Audit planning
   - Finding management
   - Action plan tracking
   - Audit history

5. **Dashboard & Reports**
   - Compliance status overview
   - Expiration alerts
   - Audit results
   - Custom reports

## Documentation

For detailed documentation, see the [PRD Document](./NORMAFLOW_PRD_MVP.md).

## License

Proprietary - All rights reserved

## Contributing

This is a private project. Please contact the maintainers for access.
